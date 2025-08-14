# 📚 BookHaven - Modern Online Bookstore

A comprehensive, modern online bookstore web application with an elegant user interface and powerful admin management system. BookHaven provides customers with a seamless book shopping experience while offering administrators complete control over inventory, orders, and customer management.

## ✨ Features

### 🛍️ Customer Experience
- **Modern Book Browsing**: Beautiful, responsive book catalog with advanced filtering and search
- **User Authentication**: Secure registration and login system with session management
- **Smart Shopping Cart**: Interactive cart with real-time updates and quantity management
- **Streamlined Checkout**: Intuitive checkout process with order confirmation
- **Order Tracking**: Comprehensive order history and status tracking
- **Contact System**: Direct communication channel with customer support
- **Responsive Design**: Optimized for desktop, tablet, and mobile devices

### 🔧 Admin Management Panel
- **Dashboard Analytics**: Comprehensive overview with statistics and quick actions
- **Inventory Management**: Complete CRUD operations for book catalog with image uploads
- **Order Processing**: Advanced order management with status updates and customer details
- **User Administration**: Full user account management and monitoring
- **Message Center**: Customer inquiry management with direct email response capabilities
- **Product Categories**: Organized book categorization system with detailed descriptions

## 🎨 Design & Technology

### **Modern UI/UX**
- **Design System**: Custom Tailwind CSS implementation with professional color palette
- **Responsive Layout**: Mobile-first design approach with adaptive layouts
- **Interactive Elements**: Hover effects, smooth transitions, and micro-interactions

### **Color Scheme**
- **Primary**: Professional blue gradient (`#0ea5e9` to `#0369a1`)
- **Sage**: Natural green tones (`#647064` to `#2e322e`)
- **Cream**: Warm neutrals (`#fefcf8` to `#955d3e`)
- **Accent**: Strategic red highlights for important actions

## 🛠️ Technology Stack

### **Frontend**
- **Framework**: Tailwind CSS for utility-first styling
- **JavaScript**: Vanilla JS with modern ES6+ features
- **Icons**: Font Awesome 6.0 for consistent iconography
- **Typography**: Google Fonts (Playfair Display + Inter)
- **Animations**: CSS transitions and transform animations

### **Backend**
- **Language**: PHP 8+ with modern practices
- **Database**: MySQL with prepared statements for security
- **Session Management**: Secure PHP sessions with proper validation
- **File Handling**: Image upload and management system
- **Security**: SQL injection prevention, XSS protection, CSRF mitigation

### **Database Architecture**
- **Users**: Comprehensive user management with role-based access
- **Products**: Rich product data with categories, descriptions, and stock management
- **Orders**: Complete order lifecycle tracking
- **Cart**: Session-based shopping cart functionality
- **Messages**: Customer communication system

## 📱 Key Pages & Functionality

### **Customer-Facing Pages**
- **Homepage** (`index.php`): Featured products with modern card layouts
- **Shop** (`shop.php`): Complete product catalog with filtering
- **Product Details**: Individual product pages with detailed information
- **Shopping Cart** (`cart.php`): Interactive cart management
- **Checkout** (`checkout.php`): Streamlined purchase process
- **User Profile** (`profile.php`): Account management with conditional layouts
- **About Us** (`about.php`): Company information and team details
- **Contact** (`contact.php`): Customer inquiry form

### **Admin Panel**
- **Dashboard** (`admin_page.php`): Analytics and quick actions overview
- **Product Management** (`admin_products.php`): Inventory CRUD operations
- **Order Management** (`admin_orders.php`): Order processing and status updates
- **User Management** (`admin_users.php`): Customer account administration
- **Message Center** (`admin_contacts.php`): Customer communication hub

## 🔐 Security Features

- **Prepared Statements**: All database queries use prepared statements
- **Session Security**: Proper session handling with validation
- **Input Sanitization**: HTML escaping and data validation
- **Access Control**: Role-based permissions for admin functions
- **File Upload Security**: Secure image handling with validation
- **CSRF Protection**: Form token validation for sensitive operations

## 📦 Installation & Setup

### **Prerequisites**
- PHP 8.0 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Modern web browser with JavaScript enabled

### **Setup Instructions**

1. **Clone the Repository**
   ```bash
   git clone https://github.com/param-chandarana/bookstore.git
   cd bookstore
   ```

2. **Database Setup**
   ```sql
   -- Create database
   CREATE DATABASE bookstore;
   
   -- Import the schema
   mysql -u username -p bookstore < shop_db.sql
   ```

3. **Configuration**
   ```php
   // Update config.php with your database credentials
   $conn = mysqli_connect('localhost', 'username', 'password', 'bookstore');
   ```

4. **Web Server Setup**
   - Place files in your web server directory
   - Ensure proper permissions for uploaded images directory

5. **Admin Account**
   - Register a new account
   - Select type 'admin' while registering
   - Or use existing admin credentials if available

## 🎯 Project Highlights

### **Modern Architecture**
- **Component-Based Design**: Reusable header/footer components
- **Responsive Layouts**: Mobile-first approach with progressive enhancement
- **Performance Optimized**: Efficient database queries and optimized assets
- **Accessibility**: Semantic HTML and keyboard navigation support

### **Advanced Features**
- **Real-Time Updates**: Dynamic cart updates and live statistics
- **Image Management**: Secure file upload with validation
- **Email Integration**: Direct customer communication capabilities
- **Analytics Dashboard**: Comprehensive business insights
- **Search Functionality**: Advanced product search and filtering

### **Professional UI Elements**
- **Modal Dialogs**: Elegant confirmation and form modals
- **Loading States**: Smooth transitions and feedback
- **Error Handling**: User-friendly error messages and validation
- **Success Feedback**: Clear confirmation messages and visual cues

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **Tailwind CSS**: For the utility-first CSS framework
- **Font Awesome**: For the comprehensive icon library
- **Google Fonts**: For the beautiful typography
- **PHP Community**: For the robust backend language

---

<div align="center">

**BookHaven** - *Literary Adventures Await* 📖✨

</div>
