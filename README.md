# E-SHIKHON - Online Learning Platform

E-SHIKHON is a comprehensive online learning platform that connects instructors and students, facilitating seamless educational experiences through course management, content delivery, and interactive learning.

## Features

### For Students
- User registration and authentication
- Browse and enroll in courses
- View course materials and video content
- Track ongoing and upcoming courses
- Download course materials
- View instructor profiles
- Edit personal profile
- Change password functionality

### For Instructors
- Instructor registration and profile management
- Course creation and management
- Upload and manage course materials
- Upload and manage video content
- View student feedback
- Track course enrollments

### Administrative Features
- Admin dashboard
- Instructor management
- Course oversight
- System monitoring

## Technology Stack

- **Frontend**: HTML, CSS, JavaScript, Bootstrap
- **Backend**: PHP
- **Database**: MySQL
- **Server**: Apache

## Installation

1. Clone the repository:
```bash
git clone https://github.com/singhosudip333/E-SHIKHON.git
```

2. Set up your XAMPP environment:
   - Install XAMPP
   - Place the project in the `htdocs` directory
   - Start Apache and MySQL services

3. Database Setup:
   - Import the database schema from `extra/project.sql`
   - Configure database connection in `connection.php`

4. Configure virtual host (optional):
   - Update your Apache configuration
   - Modify the `.htaccess` file if needed

## Project Structure

```
E-SHIKHON/
├── admin/                 # Administrative interface files
├── instructor/            # Instructor-specific functionality
├── includes/             # Common PHP includes
├── uploads/              # Uploaded files (courses, videos, etc.)
├── diagrams/             # System architecture diagrams
├── extra/                # Additional resources and documentation
├── images/               # Static image assets
├── connection.php        # Database configuration
├── index.html           # Main entry point
└── style.css            # Global styles
```

## Usage

1. Start your XAMPP server
2. Access the application through your web browser
3. Register as a student or instructor
4. Start learning or teaching!

## Security Features

- Password hashing
- Session management
- Input validation
- XSS protection
- SQL injection prevention

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Authors

- Sudip Singho - Initial work and maintenance

## Acknowledgments

- Thanks to all contributors who have helped shape E-SHIKHON
- Special thanks to the testing team and early adopters
- Bootstrap team for the fantastic UI framework

## Support

For support, email singhosudip333@gmail.com or open an issue in the repository.
