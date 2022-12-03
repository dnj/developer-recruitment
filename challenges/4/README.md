# Frontend Recruitment Test

The objective of this challenge is to build a remote control UI for a table fan.

<p align="center">
	<img width="256" alt="Fan UI Design" src="https://raw.githubusercontent.com/dnj/developer-recruitment/master/challenges/4/design/dashboard.png">
</p>


# Context 
In DNJ we do a lot of IOT stuff on daily basis. This test is UI of first WiFi-controlled fan that made in Iran.
This UI implemented as mobile application and communicates by a restful API to an embedded microcontroller on the device.

# Functionality

In this page the user can:

### Power on/off device:
When user powers on the fan, blades must rotate and head of fan must oscillate.
When user powers off device, all animations must be stop.

### Control speed of blades:
Speed of blades must control by a range slider and apply on screen animation.

### Set a mode for rotating blades:
There are 4 mode for rotating of blades and based on user selecion must apply on screen animation.
- Normal: use `linear` as `animation-timing-function`
- Oceanic: use `ease-in-out` as `animation-timing-function`
- Tropical: use `ease` as `animation-timing-function`
- Woodsy: use `cubic-bezier(0.5,0.36,0.55,0.84)` as `animation-timing-function`

### Turn on/off oscillating:

When user turn on oscillating this cause rotating the head of fan:

<p align="center">
	<img width="486" height="192" alt="Oscillating Head" src="https://raw.githubusercontent.com/dnj/developer-recruitment/master/challenges/4/design/oscillating.webp">
</p>

And obviously it should rotate when it's off.

# Steps
1. Fork this repo on your Github account.
2. Make a directory on `challenges/4/YOUR-CODE-GOES-HERE` with your github username. (e.g.: `challenges/4/YOUR-CODE-GOES-HERE/yeganemehr`)
3. Generate your project on the directory you just make.
4. Start building your component and commit & push every few step.
5. Send us a pull request when you are done.
6. Wait for us!

# About the challenge

This is a challenge, not a college test, so there are multiple correct answers.

We'll give you some requirements that must be done and for which you'll be evaluated, but you're free to choose a solution method.

What we expect to learn from you with this challenge:

- Your work style.
- How you think and solve problems.
- How you communicate.

What we expect that you learn from us:

- How we work as a team.
- Have a close look at some of the problems we face daily.


## Framework & Technologies
- Latest ReactJS version and/or VueJS 3
- SASS/SCSS/LESS
- ES6 Javascript or Typescript (highly recommended.)
- A UI framework based on Material Deisgn.
- Try to keep it simple and do not use additional libraries but there is no hard limit.


<p align="center">
	<img width="256" height="256" src="https://raw.githubusercontent.com/dnj/developer-recruitment/master/challenges/4/design/FanSticker.gif">
</p>

Wish you best of luck.