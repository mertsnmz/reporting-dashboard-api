# Laravel PSP Reporting Dashboard

This Laravel application demonstrates how to leverage the PSP Reporting API to build a custom web application dashboard that displays insightful metrics and data visualizations.

**Live Application:** https://reporting-dashboard-api-cgid.onrender.com/

## Introduction

The PSP Reporting API provides access to most of the report data in PSP, enabling you to:
- Build custom dashboards to display PSP API data
- Automate complex reporting tasks to save time

This project showcases how to integrate the PSP Reporting API with a Laravel application to create a powerful, custom reporting dashboard.

## Features

- Fetches data from the PSP Reporting API, utilizing at least 4 endpoints:
    - Merchant Login
    - Transaction Report
    - Transaction Query
    - Get Transaction
- Displays key metrics and KPIs in a visually appealing web interface
- Offers interactive data visualizations and charts
- Provides an easy-to-use interface to customize and filter reports
- Automates complex reporting tasks to save time and effort

## Installation

1. Clone the repository:
   ```
   git clone git@github.com:mertsnmz/reporting-dashboard-api.git
   ```

2. Navigate to the project directory:
   ```
   cd reporting-dashboard-api
   ```

3. Install the PHP dependencies using Composer:
   ```
   composer install
   ```

4. Install the Node.js dependencies using npm:
   ```
   npm install
   ```

5. Create a copy of the `.env.example` file and rename it to `.env`:
   ```
   cp .env.example .env
   ```

6. Generate an application key:
   ```
   php artisan key:generate
   ```

7. Update the `.env` file with your PSP Reporting API credentials and configuration:
   ```
   API_URL=api-url
   API_KEY=your-api-key
   API_SECRET=your-api-secret
   ```

8. Run the database migrations:
   ```
   php artisan migrate
   ```

9. Build the frontend assets:
   ```
   npm run build
   ```

10. Start the development server:
    ```
    php artisan serve
    ```

11. Access the web application dashboard in your browser at `http://localhost:8000`.

## Usage

- Log in to the web application using your credentials.
- Navigate through the dashboard to view various metrics, charts, and visualizations.
- Use the provided filters and options to customize the displayed data according to your needs.
- Automate reporting tasks by leveraging the power of the PSP Reporting API and Laravel's scheduling capabilities.

## API Documentation

Refer to the [PSP Reporting API Documentation](path/to/api-documentation.pdf) for detailed information on the available endpoints, request parameters, and response formats.

## Contributing

Contributions are welcome! If you find any issues or have suggestions for improvement, please open an issue or submit a pull request.

## License

This project is open-source and available under the [MIT License](LICENSE).
