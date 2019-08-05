Test Task(php)  

Description in Task.md

Dependency: Docker 

TODO:
- Add Unit Tests + make more testable code
- Test incorrect Domain Name
- Test huge json input


## Usage

### Install dependency(Symfony Validators via Composer):
`make install`

### Run:
`make run`

### Run with incorrect input json:
`make run-incorrect`

### Run with incorrect json format:
`make run-broken-json-errors-only`

### Remove (un)used docker images:
`make remove-unused-images`

### View all commands:
`make help`
