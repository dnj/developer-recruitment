# Frontend Recruitment test

The goal is to implement a 24-hour clock component with VueJS and/or ReactJS which have some additional curves around it.

<p align="center">
  <img width="201" height="201" alt="Clock" src="https://raw.githubusercontent.com/dnj/frontend-recruitment/master/challenges/2/design/clock.png">
</p>

This clock is a part of a timer UI and supposed to show user when the timer is active and when it's not.
Real technical challenge is to use no images, no canvas and ideally no SVGs. Just HTML, CSS and JS.


- Read carefully this manual. We intentionally mixed important and unimportant data on this text to test your concentrate and taste how its feel like to work with you in future.
- Try to code less and clean.
- Pay attention to graphic design, both functional and graphical details are important.
- Don't forget about compatibility. Do not develop weird technique that not-very-updated browsers don't support.
- We love to see how you test your component.
- It's ok to search and copy & paste code snippets into your work but do it smartly.
- You can use codestyle and anything else that does not mentioned specifically on this document based on your preferences.

## Functionality
The clock is 24-hour. We prefer you do not use any images or canvas but if it's difficult to draw a curve around the clock it's ok to use SVG for that part.

<p align="center">
  <img width="339" height="296" alt="Clock Explained" src="https://raw.githubusercontent.com/dnj/frontend-recruitment/master/challenges/2/design/clock-explained.jpg">
</p>

The component accepts a prop for active hours in this format:

```ts
interface ITimeRange {
	start: number; // 0 - 24
	end: number; // 0 - 24
}
interface IProps {
	activeHours: ITimeRange[];
}
```

So basically it's how to call your component:
```tsx
<div>
	<Clock :activeHours="[{start: 5, end: 13}, {start: 22, end: 24}]"/>
</div>
```

We (as testers and users of your code) guarantee that all input `ITimeRange.end > ITimeRange.start` so you don't have to get involved with prop validation.

## Steps
1. Fork this repo on your Github account.
2. Make a directory on `challenges/2/YOUR-CODE-GOES-HERE` with your github username. (e.g.: `challenges/2/YOUR-CODE-GOES-HERE/yeganemehr`)
3. Generate your project on the directory you just make.
4. Start building your component and commit & push every few step.
5. Send us a pull request when you are done.
6. Wait for us!


## Framework & Technologies
- Latest ReactJS version and/or VueJS 3
- SASS/SCSS/LESS
- ES6 Javascript or Typescript (highly recommended.)
- Try to keep it simple and do not use additional libraries but there is no hard limit.


Wish you best of luck.