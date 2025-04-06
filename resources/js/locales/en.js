export default {
    app: {
        name: 'Cultural Balakovo',
        footer: {
            copyright: ' {year} Cultural Balakovo'
        }
    },
    navigation: {
        home: 'Home',
        events: 'Events',
        myRegistrations: 'My Registrations',
        search: 'Search',
        account: 'Account',
        calendar: 'Calendar',
        eventTypes: 'Categories',
        reviews: 'Reviews',
        contacts: 'Contacts',
        about: 'About',
        backToHome: 'Back to Home',
        admin: 'Administration',
        adminDashboard: 'Dashboard',
        adminEvents: 'Manage Events',
        adminReviews: 'Review Moderation',
        adminUsers: 'Users'
    },
    home: {
        welcome: {
            title: 'Welcome to Cultural Balakovo',
            description: 'Find an interesting event and register for it',
            action: 'Find Events'
        },
        upcomingEvents: {
            title: 'Upcoming Events',
            viewDetails: 'View Details',
            noEvents: 'No upcoming events'
        },
        categories: {
            title: 'Event Categories'
        },
        actions: {
            browseEvents: 'Browse Events',
            viewAll: 'View All'
        },
        features: {
            upcoming: {
                title: 'Event Calendar',
                description: 'Convenient search for events by date, venue, and category. Stay informed about all cultural events in the city.'
            },
            community: {
                title: 'Reviews and Ratings',
                description: 'Share your impressions of the events you\'ve attended, leave reviews, and help others make choices.'
            },
            featured: {
                title: 'Available Everywhere',
                description: 'Browse events and buy tickets from any device - computer, tablet, or smartphone.'
            }
        },
        currentEvents: {
            title: 'Current Events'
        },
        recommendedEvents: {
            title: 'Recommended Events'
        }
    },
    categories: {
        cultural: 'Cultural',
        culturalDesc: 'Theaters, museums, exhibitions, classical music concerts',
        culturalIcon: 'mdi-theater',
        
        sports: 'Sports',
        sportsDesc: 'Matches, competitions, tournaments, training sessions',
        sportsIcon: 'mdi-soccer',
        
        educational: 'Educational',
        educationalDesc: 'Lectures, seminars, master classes, conferences',
        educationalIcon: 'mdi-school',
        
        entertainment: 'Entertainment',
        entertainmentDesc: 'Concerts, festivals, parties, shows',
        entertainmentIcon: 'mdi-party-popper',
        
        other: 'Other',
        otherDesc: 'Meetings, networking, workshops, and other events',
        otherIcon: 'mdi-calendar-blank'
    },
    calendar: {
        title: 'Event Calendar',
        viewDetails: 'View Details',
        noEvents: 'No events on the selected date'
    },
    eventTypes: {
        title: 'Event Categories',
        events: 'events'
    },
    reviews: {
        title: 'Reviews and Ratings',
        viewEvent: 'Go to Event',
        delete: 'Delete',
        count: '{n} reviews',
        empty: {
            title: 'No Reviews Yet',
            description: 'Be the first to leave a review',
            action: 'Write a Review'
        },
        deleteConfirm: {
            title: 'Delete Review',
            message: 'Are you sure you want to delete this review?'
        },
        status: {
            published: 'Published',
            pending: 'Pending',
            rejected: 'Rejected',
            unknown: 'Unknown',
            approved: 'Published'
        },
        actions: {
            approve: 'Approve',
            reject: 'Reject',
            delete: 'Delete',
            cancel: 'Cancel'
        },
        rating: 'Rating',
        yourRating: 'Your Rating',
        writeReview: 'Write a Review',
        form: {
            name: 'Your Name',
            email: 'Your Email',
            subject: 'Subject',
            message: 'Message',
            send: 'Send'
        }
    },
    about: {
        title: 'About Us',
        mission: {
            title: 'Mission',
            description: 'Our mission is to make cultural life in Balakovo more accessible and interesting for all residents and guests of the city.',
            features: {
                calendar: 'Event Calendar',
                calendarDesc: 'Always up-to-date information about all cultural events in the city',
                
                registration: 'Easy Registration',
                registrationDesc: 'Simple and convenient registration for events in just a few clicks',
                
                reviews: 'Honest Reviews',
                reviewsDesc: 'Rating and review system helps make the right choice'
            }
        },
        team: {
            title: 'Our Team',
            description: 'We are a small but passionate team working to improve the cultural life of the city',
            members: 'Team Members'
        },
        stats: {
            title: 'Statistics',
            events: 'Events',
            users: 'Users',
            organizers: 'Organizers'
        }
    },
    events: {
        title: 'Events in Balakovo',
        details: 'Details',
        free: 'Free',
        filters: {
            type: 'Category',
            price: 'Price',
            date: 'Date',
            location: 'Location',
            apply: 'Apply Filters',
            reset: 'Reset Filters',
            freeOnly: 'Free Events Only',
            upcoming: 'Upcoming Events Only',
            search: 'Search Events'
        },
        sort: {
            title: 'Sort by',
            date_asc: 'Date (ascending)',
            date_desc: 'Date (descending)',
            price_asc: 'Price (low to high)',
            price_desc: 'Price (high to low)',
            name_asc: 'Name (A-Z)',
            name_desc: 'Name (Z-A)'
        },
        showOnMap: 'Show on Map',
        free: 'Free',
        register: 'Register',
        registered: 'You are registered',
        already_registered: 'You are already registered',
        login_required: 'Login required to register',
        registration_error: 'Error registering for event',
        unregister: 'Cancel Registration',
        unregister_confirm: 'Are you sure you want to cancel your registration?',
        unregistration_error: 'Error canceling registration',
        past: 'Past Event',
        past_event_notice: 'This event has already ended. Registration is not available.',
        actions: {
            details: 'Details',
            register: 'Register',
            unregister: 'Cancel Registration',
            addToCalendar: 'Add to Calendar',
            share: 'Share'
        },
        details: {
            title: 'Event Details',
            description: 'Description',
            date: 'Date',
            time: 'Time',
            location: 'Location',
            locationNotSpecified: 'Location not specified',
            error: 'Failed to load event information'
        },
        reviews: {
            title: 'Reviews',
            empty: 'No reviews yet. Be the first!',
            auth_required: 'Please sign in to leave a review',
            future_event: 'Reviews can only be left after the event has taken place',
            cannot_review: 'You have already submitted a review for this event',
            not_registered: 'You need to register for the event to be able to leave a review',
            form: {
                text: 'Your review',
                submit: 'Submit review'
            }
        }
    },
    registrations: {
        empty: {
            title: 'You have no registrations yet',
            description: 'Find an interesting event and register for it',
            action: 'Find Events',
            active: 'You have no active registrations',
            past: 'You have no past events'
        },
        registered_at: 'Registered on',
        actions: {
            cancel: 'Cancel'
        },
        tabs: {
            active: 'Active',
            past: 'Past'
        }
    },
    common: {
        cancel: 'Cancel',
        save: 'Save',
        delete: 'Delete',
        edit: 'Edit'
    },
    auth: {
        login: 'Login',
        register: 'Register',
        logout: 'Logout',
        email: 'Email',
        password: 'Password',
        name: 'Name',
        firstName: 'First Name',
        lastName: 'Last Name',
        forgotPassword: 'Forgot Password?',
        noAccount: 'Don\'t have an account?',
        hasAccount: 'Already have an account?',
        signUp: 'Sign Up',
        signIn: 'Sign In',
        passwordConfirmation: 'Confirm Password',
        loginSuccess: 'Login successful',
        registerSuccess: 'Registration successful',
        logoutSuccess: 'Logout successful',
        loginRequired: 'You need to be logged in to access this page',
        adminRequired: 'You need admin rights to access this page',
        invalidCredentials: 'Invalid email or password',
        rememberMe: 'Remember Me'
    },
    admin: {
        dashboard: {
            title: 'Dashboard',
            welcome: 'Welcome to the admin dashboard',
            stats: {
                events: 'Events',
                users: 'Users',
                registrations: 'Registrations',
                reviews: 'Reviews'
            }
        },
        events: {
            title: 'Event Management',
            add: 'Add Event',
            edit: 'Edit Event',
            deleteTitle: 'Delete Event?',
            deleteConfirm: 'Are you sure you want to delete event "{title}"? This action cannot be undone.',
            table: {
                id: 'ID',
                title: 'Title',
                type: 'Type',
                date: 'Date',
                location: 'Location',
                price: 'Price',
                actions: 'Actions'
            },
            form: {
                title: 'Title',
                description: 'Description',
                location: 'Location',
                type: 'Event Type',
                price: 'Price (₽)',
                priceHint: 'Leave empty for free event',
                capacity: 'Capacity',
                date: 'Event Date',
                time: 'Start Time',
                image: 'Image URL',
                imageHint: 'Leave empty for automatic image selection'
            },
            validation: {
                titleRequired: 'Title is required',
                descriptionRequired: 'Description is required',
                locationRequired: 'Location is required',
                typeRequired: 'Event type is required',
                capacityRequired: 'Capacity is required',
                dateRequired: 'Event date is required',
                timeRequired: 'Start time is required'
            }
        },
        reviews: {
            title: 'Review Moderation',
            headers: {
                id: 'ID',
                user: 'User',
                event: 'Event',
                rating: 'Rating',
                status: 'Status',
                date: 'Date',
                actions: 'Actions'
            },
            close: 'Close',
            approve: 'Approve',
            reject: 'Reject',
            delete: 'Delete',
            status: {
                pending: 'Pending',
                approved: 'Approved',
                rejected: 'Rejected'
            },
            table: {
                id: 'ID',
                event: 'Event',
                user: 'User',
                rating: 'Rating',
                text: 'Text',
                status: 'Status',
                date: 'Date',
                actions: 'Actions'
            },
            deleteConfirm: {
                title: 'Delete Review',
                message: 'Are you sure you want to delete this review?'
            },
            actions: {
                approve: 'Approve',
                reject: 'Reject',
                delete: 'Delete',
                cancel: 'Отмена'
            },
            rating: 'Rating',
        },
        users: {
            title: 'User Management',
            table: {
                id: 'ID',
                name: 'Name',
                email: 'Email',
                role: 'Role',
                registrations: 'Registrations',
                reviews: 'Reviews',
                joined: 'Joined Date',
                actions: 'Actions'
            }
        }
    },
    common: {
        save: 'Save',
        cancel: 'Cancel',
        delete: 'Delete',
        edit: 'Edit',
        view: 'View',
        search: 'Search',
        filter: 'Filter',
        reset: 'Reset',
        apply: 'Apply',
        yes: 'Yes',
        no: 'No',
        back: 'Back',
        next: 'Next',
        loading: 'Loading...',
        noData: 'No data available',
        all: 'All'
    },
    errors: {
        notFound: {
            title: 'Page Not Found',
            description: 'The requested page does not exist or has been moved',
            action: 'Back to Home'
        },
        pageNotFound: 'Page Not Found',
        serverError: {
            title: 'Server Error',
            description: 'An error occurred while processing your request',
            action: 'Try Again'
        },
        validation: {
            required: 'This field is required',
            email: 'Please enter a valid email',
            minLength: 'Minimum length: {min} characters',
            maxLength: 'Maximum length: {max} characters',
            passwordMatch: 'Passwords do not match'
        }
    },
    contacts: {
        title: 'Contacts',
        getInTouch: 'Get in touch with us',
        phone: 'Phone',
        email: 'Email',
        address: 'Address',
        sendMessage: 'Send a message',
        form: {
            name: 'Your name',
            email: 'Your email',
            subject: 'Subject',
            message: 'Message',
            send: 'Send'
        }
    }
}
