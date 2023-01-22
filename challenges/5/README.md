# Frontend Recruitment Test

The objective of this challenge is to build a discussion commpoennt.

<p align="center">
    <img width="256" alt="Discussion UI Design" src="https://raw.githubusercontent.com/dnj/developer-recruitment/master/challenges/5/design/discussion.png">
</p>


# Context 
When we develop a web project or a exclusive ERP it's very common to have subject for discussion, like in a project overview page or in end of article page.

# Functionality

In this component discussions receive data from a array with this structure:
```ts
interface IUser {
    name: string;
    avatar?: string;
}
interface IComment {
    id: number;
    date: number; // unix timestamp in milliseconds.
    user: IUser;
    text: string;
    likes: number;
    iLikedIt: boolean;
}
interface IDiscussion extends IComment {
    replies: IComment[];
}
interface IProps {
    comments: IComment[];
}
```

example:
```tsx
const discussions: IDiscussion[] = [
    {
        id: 3,
        date: 1672576574000,
        user: {
            name: "Bessie Cooper",
            avatar: "https://www.godaddy.com/garage/wp-content/uploads/judith-kallos-BW-NEW-150x150.jpg"
        },
        text: "I think for our second compaign we can try to target a different audience. How does it sound for you?",
        likes: 2,
        iLikedIt: false,
        replies: [
            {
                id: 5,
                date: 1672581014000,
                user: {
                    name: "Marvin McKinney",
                    avatar: "https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50"
                },
                text: "Yes, that sounds good! I can think about this tomorrow. Then do we plan to start that compaign?",
                likes: 3,
                iLikedIt: true,
            },
            {
                id: 6,
                date: 1672581614000,
                user: {
                    name: "Bessie Cooper",
                    avatar: "https://www.godaddy.com/garage/wp-content/uploads/judith-kallos-BW-NEW-150x150.jpg",
                },
                text: "We plan to run the compaign on Friday - as far as I know. Do you think you will get this done by Thursday @Marvin?",
                likes: 0,
                iLikedIt: false,
            }
        ]
    },
    {
        id: 2,
        date: 1672232414000,
        user: {
            name: "Marvin McKinney",
            avatar: "https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50"
        },
        text: "The first compaign went smoothly. Please make sure to see all attachments with the results to understand the flow.",
        likes: 2,
        iLikedIt: false,
        replies: []
    },
    {
        id: 1,
        date: 1671886814000,
        user: {
            name: "Savannah Nguyen"
        },
        text: "We have just published the first campaign. Let's see the results in the 5 days and we will iterate on this.",
        likes: 50,
        iLikedIt: true,
        replies: []
    }
];

<Discussion :discussions="discussions" />
```

So based on the design, the user can send replies to just first layer of comments and like all of them.

# Submission
Push your code in your personal-public github repository then send an email to hi@dnj.co.ir, with:

- Title: [Front-end Developer] Your name;
- Body: Repository link with optional description

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
- Try to keep it simple and do not use additional libraries but there is no hard limit. (See: [KISS](https://en.wikipedia.org/wiki/KISS_principle))


<p align="center">
    <img width="256" height="256" src="https://raw.githubusercontent.com/dnj/developer-recruitment/master/challenges/5/design/sticker.gif">
</p>

Wish you best of luck.
