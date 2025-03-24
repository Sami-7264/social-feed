# Social Feed Application

A real-time social feed app built with Laravel, Inertia.js, Vue 3, and Tailwind CSS. This application allows users to create posts and comments with real-time updates without page refresh.

## Features

- **Post Creation**: Create posts that appear in the feed instantly
- **Real-Time Updates**: All posts and comments appear instantly for all users without page refresh
- **Comments System**: Comment on posts with real-time updates
- **Notification System**: Get notified when new posts are created or comments are added
- **User Authentication**: Register, login, and profile management
- **Clean UI**: Responsive and modern user interface styled with Tailwind CSS

## Technologies Used

- **Backend**:
  - Laravel (PHP Framework)
  - MySQL Database
  - Pusher for WebSocket functionality

- **Frontend**:
  - Vue 3 with Composition API
  - Inertia.js for server-side rendering
  - Tailwind CSS for styling

## Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd social-feed-app
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install JavaScript dependencies:
   ```bash
   npm install
   ```

4. Create and configure your `.env` file:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure your database and Pusher settings in the `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=social_feed
   DB_USERNAME=root
   DB_PASSWORD=

   BROADCAST_DRIVER=pusher
   PUSHER_APP_ID=your-pusher-app-id
   PUSHER_APP_KEY=your-pusher-key
   PUSHER_APP_SECRET=your-pusher-secret
   PUSHER_APP_CLUSTER=mt1
   ```

6. Run the database migrations:
   ```bash
   php artisan migrate
   ```

7. Compile the frontend assets:
   ```bash
   npm run dev
   ```

8. Start the Laravel development server:
   ```bash
   php artisan serve
   ```

9. Visit `http://localhost:8000` in your browser to see the application.

## Additional Packages

The following additional packages were used to enhance the application:

- **Laravel Breeze**: For authentication scaffolding
- **Laravel Echo**: For real-time event broadcasting
- **Pusher JS**: For WebSocket functionality

### Why These Packages?

- **Laravel Breeze** provides a minimal and clean authentication scaffold that allows for quick implementation of user management.
- **Laravel Echo and Pusher** provide a robust solution for real-time communication. This was crucial for implementing the instant updates for posts and comments without requiring page refreshes.

## Technical Implementation

### Real-time Updates

The real-time functionality is implemented using Laravel's event broadcasting system with Pusher. When a user creates a post or adds a comment:

1. The post/comment is saved to the database
2. An event is fired (PostCreated or CommentCreated)
3. Pusher broadcasts the event to all connected clients
4. Laravel Echo on the client side listens for these events and updates the UI accordingly

### Notification System

The application includes a real-time notification system that:

1. Displays a notification bell in the navigation bar
2. Shows a badge with the number of unread notifications
3. Allows users to view and mark notifications as read
4. Filters out self-notifications (users don't get notified of their own actions)

## Project Structure

### Backend

- **Models**: Post, Comment, User
- **Controllers**: PostController, CommentController
- **Events**: PostCreated, CommentCreated
- **Routes**: Web routes for post and comment management

### Frontend

- **Pages**: Home, Post/Show
- **Components**: PostForm, PostItem, PostList, CommentForm, CommentList, CommentItem, NotificationComponent
- **Layouts**: AuthenticatedLayout