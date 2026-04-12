# Contributing to Laravel Boilerplate

Thank you for considering contributing to this project! We welcome contributions from everyone.

## Code of Conduct

This project adheres to a code of conduct. By participating, you are expected to uphold this code.

## How to Contribute

### Reporting Bugs

- Use the GitHub issue tracker
- Describe the bug in detail
- Include steps to reproduce
- Provide system information (PHP version, Laravel version, etc.)

### Suggesting Enhancements

- Use the GitHub issue tracker
- Clearly describe the enhancement
- Explain why it would be useful
- Provide examples if possible

### Pull Requests

1. **Fork the repository**
2. **Create a feature branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Make your changes**
   - Follow the existing code style
   - Write tests for new features
   - Update documentation as needed

4. **Run tests and code quality checks**
   ```bash
   php artisan test
   composer pint
   composer phpstan
   composer rector --dry-run
   ```

5. **Commit your changes**
   - Use clear, descriptive commit messages
   - Follow conventional commits format:
     ```
     feat: add new feature
     fix: resolve bug
     docs: update documentation
     style: format code
     refactor: restructure code
     test: add tests
     chore: update dependencies
     ```

6. **Push to your fork**
   ```bash
   git push origin feature/your-feature-name
   ```

7. **Create a Pull Request**
   - Provide a clear description
   - Reference any related issues
   - Ensure all checks pass

## Code Style

### PHP
- Follow PSR-12 coding standards
- Use Laravel Pint for formatting: `composer pint`
- Run PHPStan for static analysis: `composer phpstan`
- Use Rector for code quality: `composer rector`

### TypeScript/Vue
- Follow Vue 3 Composition API best practices
- Use TypeScript for type safety
- Format with Prettier (configured in project)

### Testing
- Write tests for all new features
- Maintain or improve code coverage
- Use Pest for testing
- Follow AAA pattern (Arrange, Act, Assert)

## Development Workflow

1. **Setup development environment**
   ```bash
   composer install
   npm install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate
   php artisan db:seed
   ```

2. **Start development servers**
   ```bash
   php artisan serve
   npm run dev
   ```

3. **Run tests frequently**
   ```bash
   php artisan test --filter YourTest
   ```

4. **Check code quality before committing**
   ```bash
   composer pint
   composer phpstan
   npm run type-check
   ```

## Project Structure

- `app/Domain/` - Domain-driven design structure
- `app/Http/` - HTTP layer (controllers, middleware)
- `resources/js/` - Vue 3 + TypeScript frontend
- `tests/` - Pest tests (Feature + Unit)

## Questions?

Feel free to open an issue for any questions or concerns.

Thank you for contributing! 🎉
