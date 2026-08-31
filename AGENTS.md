# RISE GATE repository workflow

## Git operations

- After completing and validating a coherent requested change, Codex must commit the related files and push the current branch to `origin` without requiring a separate user request.
- Stage only files related to the current task. Never stage or overwrite unrelated user changes.
- If validation, authentication, conflicts, or scope are unclear, report the blocker instead of forcing the operation.

## Deployment operations

- Git push and server deployment are separate operations.
- A push must not automatically deploy to production.
- Deployment workflows use `workflow_dispatch` only.
- Production deployment is manually started from GitHub Actions.
- Preserve server-owned uploads, inquiries, sessions, environment files, and admin-managed data.
- Store credentials in GitHub Actions secrets. Never commit or display secrets.

## Public inquiry forms

- Treat every unauthenticated inquiry form as a potential outbound-mail relay.
- Send mail only to fixed, server-controlled recipients. Do not send automatic replies to visitor-supplied addresses by default.
- Require server-side validation, CSRF protection, a bot signal, per-IP rate limiting, a global send-rate ceiling, security logging, and an emergency mail-disable switch.
- CSRF tokens and honeypots are supplemental controls and must not be treated as sufficient bot protection.
- If mail to a visitor-supplied address is an explicit requirement, require server-verified CAPTCHA, tighter rate limits, monitoring, and a documented risk decision before release.
- Validate abuse cases before handoff, including direct POST, repeated submission, header injection, malformed or oversized input, missing CAPTCHA configuration, and emergency shutdown.

## Project-specific configuration

- Validation commands: PHP lint for every tracked PHP file; render-check the public pages when applicable.
- Demo URL: N/A
- Production URL: GitHub Actions variable `PRODUCTION_URL`
- Demo workflow: N/A
- Production workflow: `.github/workflows/deploy-production.yml`
- Protected server paths: root `.htaccess`, `data/contact_submissions.php`, `data/works.php`, `data/improvement_masters.php`, server-generated uploads, and sessions outside `public_html`.
