<p align="center">
    <img src="images/logo.svg" width="250" alt="Venton Logo">
</p>

A fast and lightweight developer CLI built with [Laravel Zero][laravel-zero], designed to simplify local workflows, manage Docker environments, and automate repetitive developer tasks

---

> Please note that **Venton Binary** is still under active development

## Installation

Clone the repository and install dependencies:

```bash
git clone https://github.com/bartvantuijn/ventonbinary.git
cd ventonbinary
composer install
```

### Adding the Binary to Your PATH

To make the `venton` command available globally, add the project path to your `~/.zshrc` or `~/.bashrc`:

```bash
export PATH="/Users/username/Projects/ventonbinary:$PATH"
```

Then reload your shell:

```bash
source ~/.zshrc
```

## Usage

Check version:

```bash
venton --version
```

List available commands:

```bash
venton list
```

---

## Commands

- `local:up`: Start local Docker containers (creates network, ensures resources, starts services).
- `local:down`: Stop and remove local Docker containers.

___

### License

Venton Binary is licensed under the _MIT License_. It's free to use, modify, and redistribute for any purpose,
including commercial use. See our [full license][license] for more details.

[laravel-zero]: https://laravel-zero.com/
[license]: LICENSE.md
