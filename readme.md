## About

This is an example event-sourced task list app.
It was built as a way for the author to learn about event sourcing, as well as to explore the spatie/laravel-event-projector package.
It is not production-ready and should not be used except as an example or for reference.
Authentication/Authorization mechanisms have been intentionally disabled.

## Installation

1. Clone the repo
2. Copy `.env.example` to `.env`
3. Fill in your test database credentials in `.env`
4. Run `composer install`
5. Run `php artisan migrate:fresh`

## Usage

The UI should be fairly self-explanatory. Here's a list of things you can do:
- Create task lists
- Create tasks for each list
- Mark tasks as complete by ticking checkboxes and clicking the button you want
- Delete tasks or task lists by clicking the delete button on each one
- Clear the application's state by clicking "Reset All" button. This truncates every table (including the events table) irreversibly.
- Seed the application with task lists (always creates 10 lists, deletes a random amount)
- Seed each list with tasks (always creates 10 tasks, completes and deletes a random amount)
- View a historic snapshot of the application's state by clicking the replay button on any event in the event list. This will allow you to navigate through task lists, but will not let you make any changes until you leave history view.

## Contributing

All contributions are welcome as long as they are within the scope of this example app.

## Security

This app should only be used in a local environment. No security precautions were contemplated for this demo.

## License

[MIT license](https://opensource.org/licenses/MIT).
