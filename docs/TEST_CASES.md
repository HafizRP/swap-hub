# Test Cases Swap Hub

Copy the table below into Excel or Google Sheets.

| No | Page / Feature | Route / URL | Test Case Description | Expected Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **1** | **Landing Page** | `/` | Open the homepage as a guest. | Page loads with Hero section, Features, and Login/Register buttons. | |
| **2** | **Authentication** | `/login` | Attempt to login with valid credentials. | Redirects to Dashboard. | |
| **3** | **Authentication** | `/register` | Register a new user account. | Account created, redirects to Dashboard. | |
| **4** | **Dashboard** | `/dashboard` | Access Dashboard as a logged-in user. | Displays simplified Sidebar, Header with "Selamat Datang", Stats cards, and filtered Notifications (System only). | |
| **5** | **Dashboard** | `/dashboard` | Verify "Notifications" widget. | Shows "No new messages" or system notifications (user_id=null). Only system messages appear. | |
| **6** | **Sidebar** | N/A | Click the "Minimize" (Chevron) button at the top of the sidebar. | Sidebar shrinks to icon-only mode. Content area expands. | |
| **7** | **Sidebar** | N/A | Check responsive behavior on Mobile (<768px). | Sidebar is hidden by default. Hamburger menu appears in Topbar. | |
| **8** | **Sidebar** | N/A | Click "Proyek Saya" link. | Redirects to `/projects?filter=my` showing only projects user belongs to. | |
| **9** | **Project List** | `/projects` | View "Cari Proyek" page. | Lists all active projects. Sidebar filters (Category, Tech Stack) are visible. | |
| **10** | **Project List** | `/projects` | Use Search Bar in "Cari Proyek". | List updates to show matching projects by title/description. | |
| **11** | **Create Project** | `/projects/create` | Click "Create New Project" -> Submit valid form. | Project creates, redirects to Project Detail page. User is Owner. | |
| **12** | **Project Detail** | `/projects/{id}` | View a project detail page. | Shows Title, Description, Tech Stack, Owner info, and "Join Project" or "Manage" button. | |
| **13** | **My Projects** | `/projects?filter=my` | Access "Proyek Saya" via sidebar. | Only displays projects where the user is a member or owner. | |
| **14** | **Profile** | `/profile/{id}` | View own profile (`/profile/me` or ID). | Shows Avatar, University, Bio, Skills tag list, and Project History. | |
| **15** | **Profile Settings** | `/profile` | Click "Pengaturan" or "Settings". | Shows Profile Information update form, Password update, Delete Account. | |
| **16** | **Workspace (Chat)** | `/chat` | Access "Workspace" menu. | Loads Chat layout. Left sidebar: Conversation list. Center: "Select a conversation". | |
| **17** | **Workspace (Chat)** | `/chat` | Select a Conversation (Project or DM). | Center panel loads messages. Tabs "Chat", "Tasks", "Files" appear on right/top. | |
| **18** | **Task Management** | `/chat` (Tasks Tab) | In a Project Chat, click "Tasks" tab. | Task Board loads (To Do, In Progress, etc.). | |
| **19** | **Task Management** | `/chat` (Tasks Tab) | Create a new Task. | Modal opens. Fill details -> Save. Task appears in "To Do". | |
| **20** | **Dark Mode** | N/A | Click Sun/Moon icon in Topbar. | UI switches to dark theme (backgrounds `#0b1120`, text white). | |
| **21** | **Logout** | `/logout` | Click Avatar -> Sign Out. | Session ends, redirects to Landing Page. | |
