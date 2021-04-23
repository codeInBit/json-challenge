## JSON FILE PROCESSOR

This application process a huge json file and saves the record in the database. It has an endpoint that accepts a file (for the purpose of the challange, a JSON file), it processes the file based on set constraint and saves the record in the database through a background job which is queued.

When the endpoint - [json-challange.test/api/upload-file](json-challange.test/api/upload-file) which accept a post request and a JSOn file as payload is hit to proccess the file, a job is dispatched and the file is processed in the background.

## Technology
This project was built with Laravel PHP while PHPCS and PHPStan are setup and configured in the codebase as static analysis tool to ensure clean, good code quality and uniform standards across the codebase.


Github Actions is also setup and configured on the code base to handle continous integration, when ever a push is made to master, Github Actions checks the codebase against some set of rules (some of which is PHPCS and PHPStan, Tests) and passes if everything is fine and if otherwise, it fails.

- To run PHPCS configuration against the codebase locally, run the command *./vendor/bin/phpcs*
- To run PHPStan configuration against the codebase locally, run the command *./vendor/bin/phpstan analyse*


## Installation
- Clone the project to your local machine
- Run the command *composer install*, to install dependencies
- Run the command *php artisan key:generate*
- If .env file diesn't exist, run the command *cp .env.example .env*
- In the .env file, update the necessary information to allow connection to a database
- Run the command *php artisan serve* to start the application
- Run the command *php artisan queue:work* to start the queue worker

## Answers to Bonus Questions
- <b>Question 1:<b> What happens when the source file grows to, say,	500 times the given size?
>><b>Answer:</b> This current implementation already covered that edge case, as it already expected that there may be cases where the file will be really huge.
<br>Hence, what I did was to break down the huge data into chunks, and then upload each chunk. This will help us maximize both memory and time efficiently.
- <b>Question 2:<b> Is your solution easily	adjustable to	different source data formats (CSV,	XML, etc)
>><b>Answer:</b> Yes, my solution is easily adaptable to different data sources.
We know that the methods/approach used in processing files are different across several file formats, if there is a need to support other file formats, only the implementation needs to be added to the class ```App\Services\ProcessFile``` and then used easily without doing any more work.
- <b>Question 3:<b> Say that another data filter would be the requirement that the credit card number must have three identical digits in sequence. How would you tackle this?
>><b>Answer:</b> This is quite straightforward, let’s assume the condition is not to process (save the record), I will create a new Filter Class in ```App\Services\Filters``` namespace, this will include the logic that checks the credit card number and confirms if it has three consecutive identical numbers, I process if it does and I discard if it doesn't.

