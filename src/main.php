<?php
require_once './vendor/autoload.php';

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

try {

    // get input
    $inputString = '';
    if (0 === ftell(STDIN)) {
        $inputString = '';
        while (!feof(STDIN)) {
            $inputString .= fread(STDIN, 1024);
        }
    } else {
        throw new \RuntimeException("Please provide content to STDIN.");
    }

    // json decode
    $contentsDecoded = json_decode($inputString, true);
    if (is_null($contentsDecoded)) {
        throw new \RuntimeException("Invalid Json input string format.");
    }

    $constraint = new Assert\Collection([
        'scheme' => new Assert\Optional(new Assert\Choice(['http', 'https'])),
        'username' =>
            new Assert\Optional(new Assert\Regex([
                "pattern" => "/^[a-zA-Z0-9]+$/",
                "message" => "Username can only consist of alphanumeric characters"
            ])),
        'password' =>
            new Assert\Optional(new Assert\Regex([
                "pattern" => "/^[a-zA-Z0-9]+$/",
                "message" => "Password can only consist of alphanumeric characters"
            ])),
        'domain_name' => new Assert\Required([
            new Assert\Callback(
                function ($object, \Symfony\Component\Validator\Context\ExecutionContext $context) {
                    if (!filter_var($object, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
                        throw  new \Symfony\Component\Validator\Exception\UnexpectedValueException($object, 'Valid Domain');
                    }
                }
            )
        ])
        ,
        'port' => new Assert\Optional(new Assert\Type("Integer")),
        'path' => new Assert\Optional(new Assert\Type("String")),
        'query' => new Assert\Optional([new Assert\Type('array')]),
        'fragment' => new Assert\Optional(new Assert\Type("String")),
        'disabled' => new Assert\Optional(new Assert\Type("Boolean")),
    ]);

    //  validate by rules
    $validator = Validation::createValidator();

    foreach ($contentsDecoded as $content) {
        $violations = $validator->validate($content, $constraint);
        // convert to output
        if (0 !== count($violations)) {
            // there are errors, now you can show them
            /**
             * @var $violation \Symfony\Component\Validator\ConstraintViolation
             */
            foreach ($violations as $violation) {
                fwrite(STDERR, $violation->getPropertyPath() . ' ' . $violation->getInvalidValue() . ' ' . $violation->getMessage() . "\n");
            }
        } else {
            $content['host'] = $content['domain_name'];

            if (!empty($content['query']) && is_array($content['query']) && count($content['query'])) {
                $content['query'] = http_build_query($content['query'], null, '&');
            }
            echo http_build_url(null, $content, HTTP_URL_REPLACE) . "\n";
        }
    }
} catch (\RuntimeException $e) {
    fwrite(STDERR, $e->getMessage() . "\n");
}