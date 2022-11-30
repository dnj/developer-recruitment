# Backend Recruitment Test

<p align="center"><img src="https://raw.githubusercontent.com/dnj/developer-recruitment/master/challenges/3/misc/logo.svg" width="100" alt="Taxi Logo"></p>

This project is a incomplete online taxi backend which developed based on [TDD](https://en.wikipedia.org/wiki/Test-driven_development) So basically you should just fill the blank with simplest code that pass the tests.

## Functionality
Idea is simple. There are drivers and passengers:

1. Users register in the system. (`POST /register`)

2. Some of users sign up for being a driver (`POST /driver`)

3. Drivers send updates about their car's coordination and retreive availble travels. (`PUT /driver`)

4. Passenger creates a travel with multiple stop spots with coordinates. (`POST /travels`)

5. Driver accept the travel. (`POST /travels/{travelId}/take`)

6. Driver go to coordinates of first spot and notify his arrival to passenger (`POST /travels/{travelId}/spots/{spotId}/arrived`).

7. Driver save an event in the system when passenger got in the car (`POST /travel/{travelId}/passenger-on-board`).

8. Driver take passenger to next destinations based on a predefined order by passenger and save an event for each arrival. (`POST /travels/{travelId}/spots/{spotId}/arrived`)

9. Travel mark as done when all of spots are passed.
(`POST /travels/{travelId}/done`)

Both parties can cancel travel (`POST /travels/{travelId}/cancel`) in some situations:
- Passenger: no driver accept the travel.
- Passenger: driver does not arrived at origin of travel.
- Driver: passenger doesn't got in the car yet.

Also, passenger can add (`POST /travels/{travelId}/spots`) or remove (`DELETE /travels/{travelId}/spots/{spotId}`) destinations on the way.

## What's included
We did it all before you then delete controllers method body codes, so these are included in the code and you don't need to write.   

- Eloquent Models (`app/Models`) and Enums (`app/Enums`)
- Exceptions (`app/Exceptions`)
- Authtorization policies (`app/Policies`) and service providers
- Http form requests (`app/Http/Requests`) and Resources (`app/Http/Resources`) 
- Database migrations (`database/migrations`)
- Model Factories (`database/factories`)
- Routes (`routes/api.php`)
- Feature tests (`tests`)

<p align="center">
  <img alt="Code Coverage Report" src="https://raw.githubusercontent.com/dnj/developer-recruitment/master/challenges/3/misc/code-coverage.png">
</p>

You can put your mind on http controllers.
Based on our implementation you need to write roughly < 200 lines of SMART code.


## Steps
1. Fork this repo on your Github account.
2. Make a directory on `challenges/3/YOUR-CODE-GOES-HERE` with your github username. (e.g.: `challenges/3/YOUR-CODE-GOES-HERE/yeganemehr`)
3. Copy `project` contents into your directory that you just made.
4. Start coding wherever it's needed.
5. Run `vendor/bin/phpunit`.
6. Send us a pull request when all tests are passed.
7. Make sure CI run successfully.
8. Wait for us!


## Technology & Framework
- PHP >= 8.1
- Laravel > 9.19


Wish you best of luck.
