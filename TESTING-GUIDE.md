# Quick Testing Guide - Livewire Chat

## 🚀 How to Test

### 1. Start the Application

```bash
# Make sure your dev server is running
php artisan serve

# In another terminal, start Vite (for assets)
npm run dev

# Make sure your queue worker is running (for broadcasting)
php artisan queue:work
```

### 2. Access the Chat

1. Login to your application
2. Navigate to `/chat`
3. You should see the conversation list on the left

### 3. Test Conversation Switching

**Expected Behavior:**
- Click on different conversations in the sidebar
- The chat window should update **without page reload**
- The URL should change to `/chat/{conversation_id}`
- Messages should load instantly

**How to Verify:**
1. Open browser DevTools (F12)
2. Go to Network tab
3. Click different conversations
4. You should see **NO full page requests** (only Livewire AJAX calls)

### 4. Test Message Sending

**Expected Behavior:**
- Type a message and press Enter or click Send
- Message should appear instantly in the chat
- Input should clear automatically
- Chat should scroll to bottom

**How to Verify:**
1. Type "Test message" in the input
2. Click Send button
3. Message should appear immediately
4. Check database: `SELECT * FROM messages ORDER BY id DESC LIMIT 1;`

### 5. Test Real-time Updates

**Expected Behavior:**
- Messages from other users appear automatically
- Desktop notification shown (if permission granted)
- No need to refresh

**How to Test:**
1. Open chat in **two different browsers** (or incognito)
2. Login as different users in each
3. Start a conversation between them
4. Send message from Browser 1
5. Message should appear in Browser 2 **automatically**

**Troubleshooting:**
- Make sure Pusher/Echo is configured correctly
- Check browser console for errors
- Verify queue worker is running
- Check `.env` for `BROADCAST_DRIVER=pusher`

### 6. Test Browser History

**Expected Behavior:**
- Back button returns to previous conversation
- Forward button goes to next conversation
- URL stays in sync

**How to Test:**
1. Click Conversation A
2. Click Conversation B
3. Click Conversation C
4. Press browser Back button → Should show Conversation B
5. Press Back again → Should show Conversation A
6. Press Forward → Should show Conversation B

### 7. Test Search

**Expected Behavior:**
- Typing in search box filters conversations
- Results update as you type (debounced)
- Clearing search shows all conversations

**How to Test:**
1. Type a conversation name in search box
2. Wait 300ms
3. Only matching conversations should show
4. Clear search → All conversations return

### 8. Test Desktop Notifications

**Expected Behavior:**
- Browser asks for notification permission
- New messages trigger desktop notifications
- Notifications show sender name and message preview

**How to Test:**
1. Grant notification permission when prompted
2. Have another user send you a message
3. Desktop notification should appear
4. Click notification → Should focus the tab

**Enable Notifications:**
```javascript
// Run in browser console
Notification.requestPermission().then(permission => {
    console.log('Notification permission:', permission);
});
```

---

## 🐛 Common Issues & Solutions

### Issue: Messages not appearing in real-time

**Possible Causes:**
1. Queue worker not running
2. Pusher credentials incorrect
3. Echo not initialized

**Solution:**
```bash
# Check queue worker
php artisan queue:work

# Check Pusher config in .env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=your_cluster

# Check browser console for Echo errors
```

### Issue: Page reloads when switching conversations

**Possible Causes:**
1. JavaScript error preventing event handling
2. Livewire not initialized

**Solution:**
1. Check browser console for errors
2. Verify Livewire scripts are loaded
3. Clear cache: `php artisan view:clear`

### Issue: URL doesn't update

**Possible Causes:**
1. JavaScript error in history management
2. Browser doesn't support pushState

**Solution:**
1. Check browser console
2. Verify browser supports HTML5 History API
3. Check `conversation-switched` event is firing

### Issue: Search not working

**Possible Causes:**
1. Livewire not processing input
2. JavaScript error

**Solution:**
1. Check browser console
2. Verify `wire:model.live.debounce.300ms` is working
3. Check network tab for Livewire requests

---

## ✅ Success Indicators

If everything is working correctly, you should see:

- ✅ **No page reloads** when switching conversations
- ✅ **Instant message sending** with immediate UI update
- ✅ **Real-time message reception** from other users
- ✅ **Desktop notifications** for new messages
- ✅ **Working browser history** (back/forward buttons)
- ✅ **Live search** filtering conversations
- ✅ **Smooth animations** and transitions
- ✅ **No console errors** in browser DevTools

---

## 📊 Performance Checks

### Network Tab
- Should see only Livewire AJAX requests
- No full page loads
- Requests should be < 100ms

### Console Tab
- No JavaScript errors
- Echo connection successful
- Livewire initialized

### Application Tab
- LocalStorage has Livewire data
- Session cookies present

---

## 🎯 Test Scenarios

### Scenario 1: New User Experience
1. Login as new user
2. Navigate to `/chat`
3. Should see empty state or existing conversations
4. Click a conversation
5. Should load messages
6. Send a message
7. Should appear instantly

### Scenario 2: Multi-user Chat
1. User A and User B both in same conversation
2. User A sends message
3. User B should see it immediately
4. User B replies
5. User A should see reply immediately
6. Both should see desktop notifications

### Scenario 3: Conversation Switching
1. User has 5+ conversations
2. Click through all conversations
3. Each should load without reload
4. URL should update for each
5. Back button should work
6. Search should filter correctly

---

## 🔍 Debugging Tips

### Enable Livewire Debug Mode
```php
// In config/livewire.php
'debug' => true,
```

### Check Livewire Requests
```javascript
// In browser console
Livewire.hook('message.sent', (message, component) => {
    console.log('Livewire request:', message);
});

Livewire.hook('message.received', (message, component) => {
    console.log('Livewire response:', message);
});
```

### Check Echo Connection
```javascript
// In browser console
window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('Echo connected!');
});

window.Echo.connector.pusher.connection.bind('error', (err) => {
    console.error('Echo error:', err);
});
```

### Monitor Events
```javascript
// In browser console
Livewire.on('conversation-switched', (event) => {
    console.log('Conversation switched:', event);
});

Livewire.on('message-sent', (event) => {
    console.log('Message sent:', event);
});
```

---

## 📝 Testing Checklist

Print this and check off as you test:

- [ ] Application starts without errors
- [ ] Chat page loads at `/chat`
- [ ] Conversation list displays
- [ ] Click conversation → loads messages (no reload)
- [ ] URL updates when switching conversations
- [ ] Send message → appears instantly
- [ ] Message persists after refresh
- [ ] Real-time: Message from other user appears
- [ ] Desktop notification shown
- [ ] Browser back button works
- [ ] Browser forward button works
- [ ] Search filters conversations
- [ ] Search clears correctly
- [ ] Mobile responsive (test on phone)
- [ ] No console errors
- [ ] No network errors

---

**Happy Testing! 🎉**

If you find any issues, check the troubleshooting section above or review the implementation docs in `LIVEWIRE-CHAT-COMPLETE.md`.
