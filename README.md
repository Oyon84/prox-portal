![Prox Portal](https://upload.wikimedia.org/wikipedia/en/thumb/2/25/Proxmox-VE-logo.svg/600px-Proxmox-VE-logo.svg.png)

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Prox-Portal

This is a personal project I started because there is a lack of good alternatives to the Proxmox VE interface. Where Proxmox on its own is great and offers so many good features, offering self services is pretty difficult and does not offer a pleasent user experiance for end-users who are in need of an VM or Container.

This project is using Livewire Volt for dynamic front-end interfaces to give a modern look and feel.

For communication with Proxmox I made use of [Saleh7/ProxmoxVE_PHP_API](https://github.com/Saleh7/ProxmoxVE_PHP_API).

In the first phase I aim to complete a dashboard solution before working on deployment of new assets. This includes:
- Status of VMs or Containers
- Start, Stop or Save VMs or Containers
- Create and delete resources (vm's and containers)
- Membership management resource pools

## Screenshots
<details>
    <summary><i>Screenshot of the dashboard interface, light and darkmode</i>i></summary>
    ![Screenshot 2024-08-02 145209](https://github.com/user-attachments/assets/a7bdab03-44a2-49f2-83eb-ef3facf3e252)
    ![Screenshot 2024-08-02 144907](https://github.com/user-attachments/assets/d49dda65-49b7-47e1-96d7-e22e8bbc1a02)
</details>

## Features so far

- Dual authentication, Laravel Session authentication followed by PVE authentication over API.
    - PVE User creation during registration, PVE account remains disabled when Laravel account is not verified yet by email.
    - Enable PVE user account when Laravel account is verified by email.
    - Checking for duplicate PVE username during registration.
- Artisan command nodes:update to import cluster nodes into the database.
- Dashboard display resources based on permissions in PVE.
    - Create stop start functionality for resources on the dashboard.
    - Light and Dark mode based on browser setting.
    - Recent task list for logged in user.

## Features working on currently

- Create views to show details about assets.
- Create a redirect if a new user is not member of a resource pool.
- Create landing page stating to either create a resource pool, or request a resource pool admin to add you.
- Light and Dark mode by toggle.

## Import nodes into database

Prox Portal lets you choose which hosts you want to use with prox portal. To import nodes into the prox portal database run the following command from the console.

```
php artisan nodes:update
```

Per node you can decide to import or not, nodes imported are available for placement of resources.

## Security Vulnerabilities

Please do not consider this project for any public facing or production type of deployment.

## License

This application is build with the Laravel framework and is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
