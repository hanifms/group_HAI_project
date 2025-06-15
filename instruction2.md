# Web Application Security: Task Distribution & Remediation

This document outlines the tasks for our web application security assignment, focusing on fixing vulnerabilities identified by the ZAP scan report. The full ZAP report (`ZAP by Checkmarx Scanning Report.pdf`) has been uploaded to the repository for your reference.

**Important:** Please work on your assigned branch (`Hanif`, `Arman`, `Iz'aan`) and do NOT push directly to `main`. Once your tasks are completed, create a Pull Request (PR) to `main`.

## 📄 ZAP Scan Report Overview

The ZAP report details various vulnerabilities found in our Laravel To-Do App. We will focus on addressing the High, Medium, and some notable Low/Informational risks.

**Key areas from the report:**

* **SQL Injection - SQLite:** (High Risk)
* **Content Security Policy (CSP) Header Not Set:** (Medium Risk)
* **Missing Anti-clickjacking Header:** (Medium Risk)
* **Big Redirect Detected (Potential Sensitive Information Leak):** (Low Risk, but important)
* **Cookie No HttpOnly Flag:** (Low Risk)
* **Cross-Domain JavaScript Source File Inclusion:** (Low Risk)
* **Server Leaks Information via "X-Powered-By" HTTP Response Header Field(s):** (Low Risk)
* **X-Content-Type-Options Header Missing:** (Low Risk)
* **Authentication Request Identified:** (Informational)
* **Session Management Response Identified:** (Informational)
* **User Agent Fuzzer:** (Informational)

## 🎯 Task Assignments

Each of us has a set of vulnerabilities to investigate, understand, and fix.

### **Hanif (Leader)**

**Focus Area:** Critical infrastructure, configuration, and overall review.

**My Tasks:**

1.  **SQL Injection - SQLite (High Risk):**
    * **Action:** This is the most critical. Investigate the `POST http://127.0.0.1:8000/login` endpoint as indicated by the ZAP report. Ensure that all database interactions, especially around authentication, strictly use Laravel Eloquent ORM or Query Builder with parameterized queries. Avoid raw SQL queries without proper binding.
2.  **Missing Anti-clickjacking Header (Medium Risk):**
    * **Action:** Implement `X-Frame-Options` or a Content Security Policy (CSP) frame-ancestors directive to prevent clickjacking. Laravel middleware can be used for this.
3.  **Server Leaks Information via "X-Powered-By" HTTP Response Header Field(s) (Low Risk):**
    * **Action:** Research how to suppress or remove the `X-Powered-By` header in your web server configuration (Nginx/Apache) or Laravel. This reduces information leakage.
4.  **Overall PR Merging & Final Report Compilation:**
    * **Action:** I will be responsible for reviewing and merging Pull Requests from Arman and Iz'aan into the `main` branch. Also, I will compile the final report document, integrating everyone's findings and fixes.

### **Arman**

**Focus Area:** HTTP Security Headers & Cross-Site Vulnerabilities.

**Your Tasks:**

1.  **Content Security Policy (CSP) Header Not Set (Medium Risk):**
    * **Action:** Implement a Content Security Policy (CSP) header. This is a powerful defense against XSS and data injection. Start with a strict policy and gradually relax it as needed for your application. Laravel allows setting this via middleware. The report highlights `GET http://127.0.0.1:8000/sitemap.xml` as an example.
2.  **X-Content-Type-Options Header Missing (Low Risk):**
    * **Action:** Implement the `X-Content-Type-Options: nosniff` header to prevent browsers from MIME-sniffing responses away from the declared content-type.
3.  **Cross-Domain JavaScript Source File Inclusion (Low Risk):**
    * **Action:** Review `GET http://127.0.0.1:8000/register` (as per report) and any other scripts. Ensure that all JavaScript source files are loaded securely and are not susceptible to cross-domain inclusion issues. This might relate to your CSP, or ensuring external scripts are from trusted CDNs/sources and properly hashed if using CSP.

### **Iz'aan**

**Focus Area:** Cookie Security, Redirects, and Informational Findings.

**Your Tasks:**

1.  **Cookie No HttpOnly Flag (Low Risk):**
    * **Action:** Ensure all cookies (especially session cookies and remember-me tokens) have the `HttpOnly` flag set. This prevents client-side scripts from accessing the cookie, mitigating certain XSS attack vectors. Laravel's session configuration handles this, but review `config/session.php`.
2.  **Big Redirect Detected (Potential Sensitive Information Leak) (Low Risk):**
    * **Action:** Investigate the `POST http://127.0.0.1:8000/login` redirect. Ensure redirects, especially after authentication, do not leak sensitive information in the URL or via referers. Implement safe redirect practices (e.g., only redirecting to internal, whitelisted URLs, using `intended()` in Laravel).
3.  **Informational Findings Review:**
    * **Action:** Review `Authentication Request Identified`, `Session Management Response Identified`, and `User Agent Fuzzer`. While informational, understand why ZAP flags these. For example, ensure Laravel's authentication and session management are robust (e.g., regenerate session ID on login, appropriate session lifetimes). You don't necessarily need to "fix" these if they are secure by design, but document your understanding.

## ✅ Workflow & Next Steps

1.  **Fetch & Switch:** Everyone, please `git fetch` and then `git checkout your-branch-name` (or `git checkout -b your-branch-name origin/your-branch-name` if it's your first time).
2.  **Implement Fixes:** Work on your assigned tasks within your branch.
3.  **Commit Regularly:** Use clear commit messages.
4.  **Push to Your Branch:** `git push origin your-branch-name` when you have meaningful progress.
5.  **Create Pull Request (PR):** Once you believe your assigned vulnerabilities are fixed, create a PR from your branch to `main` on GitHub. Describe what you fixed.
6.  **Review:** Hanif will review your PRs. Please also review each other's PRs to learn and provide feedback.
7.  **Merge:** Once approved, Hanif will merge the PR.
8.  **Re-scan & Verify:** After each merge to `main`, Hanif will run a new ZAP scan to confirm fixes and ensure no new issues arose.
9.  **Report Contribution:** Everyone will be responsible for documenting their assigned vulnerabilities, the fixes implemented, and the verification steps for the final report.

Let's get this done! If you have questions about your specific tasks, please refer to the ZAP report and conduct some online research regarding Laravel best practices for those vulnerabilities. Also feel free to chat in whastap
