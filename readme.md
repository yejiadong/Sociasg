![alt text](https://github.com/yejiadong/Sociasg/blob/master/assets/images/logo.png)
# Sociasg
This was a product conceptualised for the [Codefiesta](https://codefiesta.sg/) 2021 hackathon.

![alt text](https://github.com/yejiadong/Sociasg/blob/master/assets/images/loginpage.png)
Check out the actual hosted prototype [here](https://www.yejiadong.xyz/sociasg)

Watch a demo of the product [here](https://www.youtube.com/watch?v=OVOB0lqMlNU) in order to get started!

View the presentation slide deck [here](https://docs.google.com/presentation/d/1Zh9gNYrab8BWlmbgokpuzLTRPTv3Amw9lOwHRPat7YY/edit?usp=sharing)

## Inspiration
Many friends and relatives of ours are studying and working overseas, and when we have the opportunity to converse with them, they inevitably bring up their experiences of homesickness. This affliction is somewhat alleviated by measures such as care packages from home, but what many of them miss is interacting with other Singaporeans, who can be a rarity where they stay. We came to understand this as a common issue troubling overseas Singaporeans, waiting to be addressed, and thus Sociasg was born.
## What it does
Sociasg is a **simple-to-navigate** web application enabling overseas Singaporeans to find communities centred around a **common interest**. Users participate in events organised on the platform which are based on shared hobbies, and through doing so, form friendships through **meaningful shared experiences** and **sustained interaction**.
## How we built it
After the needs analysis and user research were done, we isolated four critical features that were prioritised for the prototype stage. They are the following:
- Basic login / registering functionality
- Ability to add and view groups / activities
- Ability to add and view sessions within the groups / activities
- Basic ability to search and filter activities by country and interest preferences

In order to strive for a simple, yet modern UI, we adapted an open-source Bootstrap template and created basic pages that were sufficiently clean for a lean and user-friendly web application. In order to store user, group, and session information, we made use of a local mysqli database, with PHP selected as the server-side language. Following this, we immediately launched into the actual implementation, and refining elements of the web application along the way. On Sunday, the entire team gathered to "red-team" the application, in order to continue to fine-tune and suggest improvements that can be potentially implemented in subsequent versions.

## Tech Stack
- [Bootstrap](https://getbootstrap.com/)
- [Javascript](https://www.javascript.com/)
- [php](https://www.php.net/)
- [mysql](https://www.mysql.com/)

## Challenges we ran into
Our major challenge was ensuring that we prioritised the correct elements due to time constraints. With only 2-3 days to build a functional prototype that was fit for demonstration, and varying levels of web-development experience amongst team members, it was especially critical for us to work together as a team, while working on tasks that we were the strongest and most proficient on. This eventually allowed us to pull together a functional prototype, with user research and needs analysis carried out in our presentation slides. Our mentor also gave valuable feedback on our presentation that we are thankful for that allowed us to explore better ways to better streamline our presentation.

Another challenge on the technical front was selecting the appropriate tech stack that we were familiar with, and yet gave us sufficient ability to implement a basic prototype. Many front-end tools were available, such as React, and these could have provided us with much "cooler" features. However, they are not as simple, and straightforward to use. On the back-end, tools such as django were considered, but were eventually dropped due to the lack of need of the functionality that they provided. 
## Accomplishments that we're proud of
None of us had any prior experience with hackathons, so we came into this hoping to learn a lot but unsure of what we could actually do. Honestly, we’re pretty stoked that we have a functioning prototype!
## What we learned
When it comes to designing a product, the importance of listening to users’ concerns cannot be understated. We must be ready to vigorously defend every aspect of our product according to whether it addresses genuine user problems.
## What's next for us
We intend to improve our group and event recommendation system such that it is better tailored to users’ preferences, and work on adding further functionality to the chat such as pinning messages.

## Authors

- [Ye Jiadong](https://www.linkedin.com/in/yejiadong/)
- [Kerk Peiyong](https://www.linkedin.com/in/kerkpeiyong/)
- [Chan Junda](https://www.linkedin.com/in/chan-jun-da-500457204/)
- Li Gang

## Version History

* v2
    * Various bug fixes and optimizations
    * Added chat and sessions into groups
* v1
    * Initial Prototype
    * Authentication and group creation and viewing


## Acknowledgements
- [Rentkit](https://easetemplate.com/downloads/rentkit-directory-listing-bootstrap-theme/)
- [bootstrapdash](https://www.bootstrapdash.com/product/free-bootstrap-login/)
- [Unsplash Images](unsplash.com)