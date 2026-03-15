 📦 SARPRAS CORE - Integrated Asset Management

[![PHP Version](https://img.shields.io/badge/php-%3E%3D%208.2-8892bf.svg?style=flat-square)](https://php.net)
[![Framework](https://img.shields.io/badge/framework-CodeIgniter%204-EF4223?style=flat-square)](https://codeigniter.com)
[![UI](https://img.shields.io/badge/ui-Bootstrap%205-7952b3?style=flat-square)](https://getbootstrap.com)

**SARPRAS CORE** is a modern web-based application designed to streamline the management of office supplies and assets. Featuring a real-time dashboard and QR-code integration, it provides an effortless experience for borrowing assets and requesting supplies.

---

## ✨ Key Features

-   **Adaptive Dashboard**: A sleek, high-contrast interface with **Real-time Clock** and **Live Activity Feed**.
-   **Smart Dark Mode**: Native support for Dark/Light themes with automatic system detection and manual toggle.
-   **QR-Code Scanner**: Integrated scanning system for quick asset borrowing using the device's camera.
-   **Real-time Updates**: AJAX-powered transaction history that stays updated without page refreshes.
-   **Mobile-First Design**: Optimized for both desktop and mobile use with a glassmorphism aesthetic.

---

## 🛠️ Built With

-   **Core:** [CodeIgniter 4.x](https://codeigniter.com)
-   **Frontend:** Bootstrap 5, FontAwesome 6, and Plus Jakarta Sans Typography.
-   **Libraries:** [Html5-QRCode](https://github.com/mebjas/html5-qrcode) for scanning capabilities.
-   **Authentication:** Integrated Session-based Auth for secure access.

---

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone [https://github.com/yourusername/sarpras-core.git](https://github.com/yourusername/sarpras-core.git)
   cd sarpras-core
Install DependenciesBashcomposer install
Environment SetupCopy env to .envSet your database credentials:Cuplikan kodedatabase.default.hostname = localhost
database.default.database = your_db_name
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
Run Migrations (Optional)Bashphp spark migrate
Launch ApplicationBashphp spark serve
📸 PreviewLight Mode DashboardDark Mode Dashboard(Note: Replace these placeholders with your actual screenshots for maximum impact!)🔒 SecurityThis application implements security best practices:Public Folder Isolation: The index.php is located in the /public folder to prevent direct access to system files.Escaped Output: All data displayed uses esc() to prevent XSS attacks.CSRF Protection: Native CodeIgniter 4 security filters enabled.🤝 ContributingContributions, issues, and feature requests are welcome! Feel free to check the issues page.📝 LicenseDistributed under the MIT License. See LICENSE for more information.Developed with ❤️ by [Your Name]
---





