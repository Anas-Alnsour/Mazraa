# 🌾 Mazraa.com - The Ultimate Farm Booking & Logistics Ecosystem

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![Stripe](https://img.shields.io/badge/Stripe-626CD9?style=for-the-badge&logo=Stripe&logoColor=white)
![Google Maps](https://img.shields.io/badge/Google_Maps-4285F4?style=for-the-badge&logo=googlemaps&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

**Mazraa.com** is an enterprise-level, highly scalable platform designed to revolutionize the private farm booking industry in Jordan. It seamlessly integrates luxury farm rentals with a full-scale e-commerce supply marketplace and a custom geographical logistics network.

##  Core Architecture (7-Role Micro-Ecosystem)
The system is built on a highly decoupled multi-auth architecture, supporting 7 distinct portals:
1. **Super Admin (God Mode):** Full financial ledger, ecosystem governance, and support ticket management.
2. **Farm Owner:** Dashboard to manage listings, track booking history, and view chronological profit charts.
3. **End User (Customer):** Explore farms, book shifts, manage favorites, order supplies, and request shuttle transports.
4. **Supply Company:** Manage marketplace inventory (BBQ, food, drinks) tied to specific Jordanian governorates.
5. **Transport Company:** Manage vehicle fleets (Coasters, VIP Vans) and logistics across Jordan.
6. **Supply Driver:** A dedicated interface to receive and fulfill marketplace orders.
7. **Transport Driver:** A dedicated interface to manage shuttle trips and track route statuses.

##  Advanced Engineering Features
- **Fair Dispatch Engine (Round-Robin):** A custom load-balancing algorithm that automatically dispatches transport and supply orders to drivers within the **STRICT SAME governorate**, intelligently prioritizing drivers with the lowest monthly completed tasks.
- **Automated Financial Reconciliation (Stripe Deltas):** Modifying a booking dynamically calculates complex price differences. Upgrades automatically generate new **Stripe Checkout Sessions**, while downgrades trigger automated **API Partial Refunds**.
- **Polymorphic Financial Ledger:** A robust accounting system tracking thousands of JOD across Farm Owner shares, Logistics payouts, and Platform commissions.
- **Universal Google Maps Integration:** Precision Lat/Lng pinning, interactive visual route mapping, and customized map UI logic.
- **OMNI-Seeded Infrastructure:** The database is rigorously tested with 6-months of historical, scenario-driven Arabic data covering bookings, supply orders, multi-level reviews, blocked calendar dates, and polymorphic system notifications.

##  Tech Stack
- **Backend:** Laravel 11, PHP 8.2, MySQL
- **Frontend:** Blade, Tailwind CSS, Alpine.js (Dark Mode Optimized UI)
- **Integrations:** Stripe API, Google Maps JS SDK

---
*Developed as a Masterpiece CS Graduation Project by Anas Alnsour - Hashemite University.*
