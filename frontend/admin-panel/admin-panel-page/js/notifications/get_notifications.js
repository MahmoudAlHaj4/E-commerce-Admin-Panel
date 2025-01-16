$(document).ready(function() {
    const $notificationsDropdown = $('#alertsDropdown'); // Update to your notification dropdown ID
    const $notificationList = $notificationsDropdown.find('.dropdown-list'); // The dropdown list for notifications
    const $notificationCounter = $notificationsDropdown.find('.badge-counter'); // The badge for notification count

    const loadNotifications = async () => {
        try {
            const response = await axios.get('http://localhost:3000/e-commerce-inventory-management/backend/notifications/get.php');
            if (response.data.status === 'success') {
                const notifications = response.data.notification;
    
                // Update badge count
                $('.badge-counter').text(notifications.length || 0); // Update badge count
    
                // Clear existing notifications
                const $notificationContainer = $('.notification-container');
                $notificationContainer.empty(); // Clear previous notifications
    
                if (notifications.length > 0) {
                    notifications.forEach(notification => {
                        $notificationContainer.append(`
                            <a class="dropdown-item d-flex align-items-center">
                                <div>
                                    <div class="small text-gray-500">${notification.created_at}</div>
                                    <span class="font-weight-bold">${notification.message}</span>
                                </div>
                            </a>
                        `);
                    });
                } else {
                    $notificationContainer.append('<a class="dropdown-item text-center small text-gray-500">No new notifications</a>');
                }
            } else {
                console.error('Failed to fetch notifications:', response.data.message);
            }
        } catch (error) {
            console.error('Error fetching notifications:', error);
        }
    };
    
    // Call the loadNotifications function to fetch notifications
    loadNotifications();
    
});
