# Laravel To-Do App Project

This repository contains our collaborative Laravel project. This `instructions.md` will guide you on how to set up the project and, most importantly, how to work effectively with Git and GitHub in a team environment using branches.

---

## 🚀 Getting Started

### 1. Cloning the Repository

To get a local copy of the project, use the following command:

```bash
git clone https://github.com/hanifms/group_HAI_project.git
````

### 2\. Navigate to the Project Directory

```bash
cd group_HAI_project
```

### 3\. Install Composer Dependencies

```bash
composer install
```

### 4\. Create and Configure Environment File

find the file with the extension .env.example
edit it to suit your database setup
rename file to .env only
if not work try the below commands
```bash
cp .env.example .env
php artisan key:generate
```

Open the newly created `.env` file and configure your database connection and any other necessary environment variables.

### 5\. Run Migrations (and Seeders, if any)

```bash
php artisan migrate
# Might not perlu but just in case: php artisan db:seed
```

### 6\. Start the Development Server

```bash
php artisan serve
```

You should now be able to access the application in your web browser, typically at `http://127.0.0.1:8000`.

if cannot run try npm run dev also on another terminal
-----

## 🤝 Collaborative Git Workflow (Branches)

**It is crucial that everyone works on their own dedicated branch to prevent conflicts and ensure a smooth development process.** The `main` branch should always remain stable.

### Your Dedicated Branches:

  * **Hanif:** `Hanif`
  * **Arman:** `Arman`
  * **Iz'aan:** `Iz'aan`

### How to Work on Your Branch

Follow these steps **every time** you start working on the project or need to push your changes:

1.  **Navigate to the Project Directory:**
    Ensure you are in the root directory of your cloned project in your terminal.

2.  **Fetch Latest Changes from GitHub:**
    Before starting work, always `fetch` to get the most recent information about all branches from the remote repository.

    ```bash
    git fetch
    ```

3.  **Switch to Your Dedicated Branch:**
    This command ensures you are working on your own isolated set of changes.

      * **If this is your first time switching to your branch locally:**
        ```bash
        git checkout -b your-branch-name origin/your-branch-name
        # Example for Arman: git checkout -b Arman origin/Arman
        ```
      * **If you have already created your local branch previously:**
        ```bash
        git checkout your-branch-name
        # Example for Hanif: git checkout Hanif
        ```

4.  **Pull Latest Changes (Important\!):**
    Before making changes, pull any updates that might have been pushed to your branch by yourself (from another machine) or others (though generally, only you should be pushing to your feature branch).

    ```bash
    git pull origin your-branch-name
    # Example: git pull origin Iz'aan
    ```

5.  **Work on Your Code:**
    Make your desired changes, add new features, fix bugs, etc.

6.  **Stage Your Changes:**
    Tell Git which changes you want to include in your next commit.

    ```bash
    git add .
    # Or for specific files: git add app/Http/Controllers/TaskController.php
    ```

7.  **Commit Your Changes:**
    Save your staged changes to your local branch history with a meaningful message.

    ```bash
    git commit -m "Brief description of the changes you made"
    # Example: git commit -m "Added task deletion functionality"
    ```

8.  **Push Your Changes to GitHub:**
    Upload your committed changes from your local branch to your corresponding branch on GitHub.

    ```bash
    git push origin your-branch-name
    # Example: git push origin Arman
    ```

-----

## 🔄 Keeping Your Branch Up-to-Date with `main`

As others merge their code into `main`, your branch might become out of sync. To avoid large conflicts later, it's good practice to periodically update your feature branch with the latest changes from `main`.

1.  **Switch to `main`:**

    ```bash
    git checkout main
    ```

2.  **Pull the Latest `main` from GitHub:**

    ```bash
    git pull origin main
    ```

3.  **Switch back to Your Branch:**

    ```bash
    git checkout your-branch-name
    # Example: git checkout Hanif
    ```

4.  **Merge `main` into Your Branch:**
    This brings the `main` branch's changes into your current branch.

    ```bash
    git merge main
    ```

      * **Resolve any merge conflicts** that appear. Git will guide you.
      * After resolving, commit the merge: `git commit -m "Merged latest main into my-branch"` (Git often auto-generates this message).

5.  **Push the Updated Branch:**

    ```bash
    git push origin your-branch-name
    ```

-----

## 🚀 Creating a Pull Request (PR)

When your feature or task is complete on your branch, you will create a Pull Request on GitHub to merge your changes into `main`. This allows for code review before integration.

1.  **Ensure your branch is pushed** to GitHub (as per step 8 in "How to Work on Your Branch").
2.  Go to your repository on **GitHub.com**.
3.  GitHub will often show a banner suggesting you create a PR from your recently pushed branch. Click it, or go to the **"Pull requests" tab** and click **"New pull request"**.
4.  Set the **base branch** to `main` and the **compare branch** to your `your-branch-name`.
5.  Add a clear **title** and **description** for your PR, explaining what changes you've made.
6.  Assign reviewers (your group mates) if desired.
7.  Create the Pull Request.
8.  **Discuss and review** the changes. Address any feedback.
9.  Once approved and all conflicts resolved, **merge the Pull Request** into `main`.

-----

Happy coding, team\! Let's build something great.

```
```
