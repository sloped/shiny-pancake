# Homework

Allows for managing schedules. 

##Routes

PUT `/api/shifts/:shift_id` - Allows for updating a shift start and end time, and the employee assigned to the shift. 

POST `/api/shifts/` - Allows for creating new shifts

GET `/api/shifts?start_time=&end_time=` - Allows for retrieving all shifts assigned to a logged in employee or all shifts if a manager. Can specify a `start_time` or `end_time`. `start_time` default to current time if not passed. 

GET `/user/:user_id` - If a manager, a user may view the contact details of any given employee. 

GET `/shiftmates` - Shows all employees a user is scheduled to work with. 

GET `/hours?week_start` - Shows how many hours an employee has worked for a week starting with `week_start`


##Todos

- [ ] Implement appropriate authentication methods, including Passport backed token auth and OAuth
- [ ] Improve the validation of dates
- [ ] Move Validators into their own classes
- [ ] Remove unnecessary fields from the responses, such as created and updated by dates
- [ ] Add a front end for viewing information returned by the API
- [ ] Document API with Api Blueprint or Swagger

