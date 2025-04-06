import { ref } from 'vue';

// Simple event bus implementation
const listeners = {};

export const eventBus = {
  // Subscribe to an event
  on(event, callback) {
    if (!listeners[event]) {
      listeners[event] = [];
    }
    listeners[event].push(callback);
    
    // Return unsubscribe function
    return () => {
      this.off(event, callback);
    };
  },
  
  // Unsubscribe from an event
  off(event, callback) {
    if (!listeners[event]) return;
    
    if (callback) {
      listeners[event] = listeners[event].filter(cb => cb !== callback);
    } else {
      listeners[event] = [];
    }
  },
  
  // Emit an event
  emit(event, ...args) {
    if (!listeners[event]) return;
    
    listeners[event].forEach(callback => {
      callback(...args);
    });
  }
};

// Auth state that can be shared across components
export const authState = {
  user: ref(null),
  isAuthenticated: ref(false),
  
  setUser(userData) {
    this.user.value = userData;
    this.isAuthenticated.value = !!userData;
    eventBus.emit('auth:changed', userData);
  },
  
  clearUser() {
    this.user.value = null;
    this.isAuthenticated.value = false;
    eventBus.emit('auth:changed', null);
  }
};
