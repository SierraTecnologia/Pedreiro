# Contributing to Pedreiro

Thank you for considering contributing to Pedreiro! This document outlines the guidelines and best practices for contributing to this project.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Workflow](#development-workflow)
- [Coding Standards](#coding-standards)
- [Testing](#testing)
- [Pull Request Process](#pull-request-process)
- [Reporting Bugs](#reporting-bugs)
- [Suggesting Features](#suggesting-features)

## Code of Conduct

We expect all contributors to:

- Be respectful and considerate
- Welcome newcomers and help them learn
- Focus on what is best for the community
- Show empathy towards other community members

## Getting Started

### Prerequisites

- PHP 7.2 or higher (PHP 8.0+ recommended)
- Composer
- Git

### Setting Up Development Environment

1. **Fork the repository** on GitHub

2. **Clone your fork** locally:
   ```bash
   git clone https://github.com/YOUR_USERNAME/Pedreiro.git
   cd Pedreiro
   ```

3. **Add upstream remote**:
   ```bash
   git remote add upstream https://github.com/SierraTecnologia/Pedreiro.git
   ```

4. **Install dependencies**:
   ```bash
   composer install
   ```

5. **Create a feature branch**:
   ```bash
   git checkout -b feature/your-feature-name
   ```

## Development Workflow

### Branch Naming Convention

Use descriptive branch names following this pattern:

- `feature/feature-name` - For new features
- `fix/bug-description` - For bug fixes
- `docs/what-changed` - For documentation updates
- `refactor/what-changed` - For code refactoring
- `test/what-added` - For adding tests

### Commit Messages

Follow these guidelines for commit messages:

- Use the present tense ("Add feature" not "Added feature")
- Use the imperative mood ("Move cursor to..." not "Moves cursor to...")
- Limit the first line to 60 characters or less
- Reference issues and pull requests when relevant

**Good examples:**
```
Add export functionality for CSV format
Fix validation error on datetime fields
Update README installation instructions
Refactor form field handler architecture
```

**Bad examples:**
```
fixed bug
updates
wip
```

### GrumPHP Git Hooks

This project uses GrumPHP to enforce quality standards. Git hooks will automatically run on:

**Pre-commit checks:**
- File size validation (max 10MB)
- Commit message format validation
- Blacklisted keywords check (die, var_dump, exit)
- Git branch name validation

**Pre-push checks:**
- Psalm static analysis

To skip hooks (not recommended):
```bash
git commit --no-verify
```

## Coding Standards

### PHP Coding Style

We follow PSR-2 coding standards with additional rules defined in `.php_cs.dist`.

**Run PHP CS Fixer before committing:**
```bash
composer format
```

**Key rules:**
- Use short array syntax (`[]` not `array()`)
- Sort imports alphabetically
- Remove unused imports
- Add blank lines before returns, breaks, and throws
- Use proper PHPDoc blocks

### Static Analysis

We use both Psalm and PHPStan for static analysis.

**Run Psalm:**
```bash
composer psalm
```

**Run PHPStan:**
```bash
./vendor/bin/phpstan analyse
```

Fix any errors or warnings before submitting your pull request.

## Testing

### Writing Tests

- Write tests for all new features
- Update tests when modifying existing features
- Follow the existing test structure in the `tests/` directory

### Test Structure

```
tests/
├── Integration/        # Integration tests
├── Controllers/        # Test controllers
├── Models/            # Test models
└── TestCase.php       # Base test case
```

### Running Tests

**Run all tests:**
```bash
composer test
```

**Run tests with coverage:**
```bash
composer test-coverage
```

**Run specific test:**
```bash
vendor/bin/phpunit tests/Integration/CrudTest.php
```

### Test Requirements

- All tests must pass before submitting a PR
- Aim for high code coverage (80%+)
- Test both success and failure scenarios
- Test edge cases and boundary conditions

## Pull Request Process

### Before Submitting

1. **Update from upstream:**
   ```bash
   git checkout master
   git pull upstream master
   git checkout your-feature-branch
   git rebase master
   ```

2. **Run quality checks:**
   ```bash
   composer test
   composer format
   composer psalm
   ./vendor/bin/phpstan analyse
   ```

3. **Update documentation** if needed

4. **Commit your changes** with clear messages

5. **Push to your fork:**
   ```bash
   git push origin your-feature-branch
   ```

### Submitting the Pull Request

1. Go to the [Pedreiro repository](https://github.com/SierraTecnologia/Pedreiro)
2. Click "New Pull Request"
3. Select your fork and branch
4. Fill in the PR template with:
   - Clear description of changes
   - Motivation and context
   - Related issues
   - Testing performed
   - Screenshots (if UI changes)

### PR Review Process

- Maintainers will review your PR
- Address any requested changes
- Keep the discussion professional and constructive
- Be patient - reviews may take time

### PR Checklist

- [ ] Code follows PSR-2 standards
- [ ] All tests pass
- [ ] New tests added for new features
- [ ] Documentation updated
- [ ] No merge conflicts
- [ ] Commit messages are clear
- [ ] Static analysis passes (Psalm & PHPStan)
- [ ] No debug code (var_dump, dd, etc.)

## Reporting Bugs

### Before Reporting

- Check existing issues to avoid duplicates
- Verify the bug exists in the latest version
- Collect relevant information

### Bug Report Template

**Title:** Clear, concise description

**Description:**
- What happened?
- What did you expect to happen?
- Steps to reproduce
- Pedreiro version
- PHP version
- Laravel version
- Stack trace (if applicable)

**Example:**
```markdown
## Bug: Form validation fails for datetime fields

**Steps to reproduce:**
1. Create a model with datetime field
2. Submit form with valid datetime
3. Validation fails with "invalid format" error

**Expected:** Validation should pass
**Actual:** Validation fails

**Environment:**
- Pedreiro: 0.4.0
- PHP: 8.0.15
- Laravel: 8.75.0

**Stack trace:**
[paste stack trace]
```

## Suggesting Features

### Feature Request Template

**Title:** Feature name

**Description:**
- What problem does this solve?
- How would this feature work?
- Are there alternatives?
- Additional context

**Example:**
```markdown
## Feature: Bulk delete functionality

**Problem:** Currently, users can only delete records one at a time

**Solution:** Add checkbox selection with "Delete selected" button

**Alternatives:**
- Archive instead of delete
- Soft delete option

**Additional context:**
Many admin panels have this feature (screenshot attached)
```

## Development Guidelines

### Code Quality

- Write clean, readable code
- Add comments for complex logic
- Keep functions small and focused
- Follow SOLID principles
- Avoid code duplication

### Performance

- Optimize database queries
- Use lazy loading when appropriate
- Cache expensive operations
- Profile code for bottlenecks

### Security

- Validate all user input
- Use prepared statements
- Implement CSRF protection
- Follow Laravel security best practices
- Never commit sensitive data

### Documentation

- Update README.md for major features
- Add PHPDoc blocks for public methods
- Include code examples
- Keep documentation accurate

## Questions?

If you have questions:

1. Check the [documentation](docs/)
2. Search [existing issues](https://github.com/SierraTecnologia/Pedreiro/issues)
3. Create a new issue with the "question" label

## License

By contributing, you agree that your contributions will be licensed under the MIT License.

## Recognition

Contributors will be recognized in:
- GitHub contributors list
- Release notes
- CHANGELOG.md

Thank you for contributing to Pedreiro! 🛠️
