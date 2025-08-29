# hostel-world-blog

Base repo & plugin for fixing bugs and resolving issues in the blog website.

<img width="1236" height="715" alt="Screenshot 2025-08-29 at 2 31 36 PM" src="https://github.com/user-attachments/assets/3b126f7f-3954-4930-882a-b4f592df1420" />

---

> <span style="color:red">**PRE-REQUISITES:**</span>
>
> As a first step, you should have Composer, Yarn & Docker installed on your computer.

## Getting Started

To begin, clone the Git repo by running the following command on your terminal like so:

```bash
git clone https://github.com/ytscar/hostel-world-blog
```

Once you have cloned the repo to your local machine, run the following command to install the project dependencies and build everything correctly:

```bash
yarn boot
```

This will spin up a local WP env instance for your local at:

```bash
http://hostel-world-blog.localhost:8274
```

Next, some minor adjustments will need to be done to ensure your local setup works correctly. Please run the following command:

```bash
yarn setup
```

### Unit Tests

To run tests, you can run the following command from your terminal:

```bash
yarn test
```
