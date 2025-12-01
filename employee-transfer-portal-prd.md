# Employee Transfer Online Portal - Product Requirements Document

## Project Overview

Build a Laravel web application that enables Namibian government employees to find and arrange employment transfers with other employees across different locations and ministries. The system matches employees based on their current location and preferred transfer destinations, facilitating mutual transfers.

### Core Concept
Employee A in Town X wants to move to Town Y. Employee B in Town Y wants to move to Town X. The system matches them and allows them to connect and arrange the transfer themselves.

### Tech Stack
- **Backend**: Laravel 11+ (PHP 8.2+)
- **Frontend**: Blade templates with Livewire OR Inertia.js with Vue 3
- **CSS**: Tailwind CSS
- **Database**: MySQL 8.0+ or PostgreSQL 15+
- **Authentication**: Laravel Breeze or Fortify with email verification
- **Email**: Laravel Mail with queue support
- **File Storage**: Laravel Storage (local or S3 for profile pictures)
- **Admin Panel**: Filament PHP (optional but recommended)

---

## Database Schema

### Users Table
```
users
├── id (bigint, primary key)
├── username (string, unique)
├── email (string, unique)
├── email_verified_at (timestamp, nullable)
├── password (string)
├── remember_token (string, nullable)
├── is_admin (boolean, default: false)
├── is_active (boolean, default: true)
├── created_at (timestamp)
└── updated_at (timestamp)
```

### Employee Profiles Table
```
employee_profiles
├── id (bigint, primary key)
├── user_id (foreign key -> users.id, unique)
├── first_name (string)
├── last_name (string)
├── employee_number (string, nullable)
├── job_grade (string, nullable)
├── employer_id (foreign key -> employers.id)
├── current_region_id (foreign key -> regions.id)
├── current_town_id (foreign key -> towns.id)
├── contact_number (string)
├── alternative_contact_number (string, nullable)
├── preferred_communication (enum: 'email', 'phone', 'both')
├── profile_picture (string, nullable)
├── probation_status (enum: 'completed', 'on_probation', 'sick_leave', 'rehabilitation', 'under_investigation')
├── probation_notes (text, nullable)
├── is_available_for_transfer (boolean, default: true)
├── created_at (timestamp)
└── updated_at (timestamp)
```

### Preferred Locations Table (Pivot)
```
preferred_locations
├── id (bigint, primary key)
├── employee_profile_id (foreign key -> employee_profiles.id)
├── region_id (foreign key -> regions.id)
├── town_id (foreign key -> towns.id, nullable)
├── priority (integer: 1, 2, or 3)
├── created_at (timestamp)
└── updated_at (timestamp)

Unique constraint: (employee_profile_id, priority)
```

### Regions Table
```
regions
├── id (bigint, primary key)
├── name (string, unique)
├── code (string, unique)
├── is_active (boolean, default: true)
├── created_at (timestamp)
└── updated_at (timestamp)
```

### Towns Table
```
towns
├── id (bigint, primary key)
├── region_id (foreign key -> regions.id)
├── name (string)
├── is_active (boolean, default: true)
├── created_at (timestamp)
└── updated_at (timestamp)

Unique constraint: (region_id, name)
```

### Employers Table (Ministries/Departments)
```
employers
├── id (bigint, primary key)
├── name (string, unique)
├── abbreviation (string, nullable)
├── is_active (boolean, default: true)
├── created_at (timestamp)
└── updated_at (timestamp)
```

### Transfer Requests Table
```
transfer_requests
├── id (bigint, primary key)
├── requester_id (foreign key -> employee_profiles.id)
├── requestee_id (foreign key -> employee_profiles.id)
├── status (enum: 'pending', 'accepted', 'declined', 'cancelled', 'expired')
├── message (text, nullable)
├── responded_at (timestamp, nullable)
├── expires_at (timestamp, nullable)
├── created_at (timestamp)
└── updated_at (timestamp)

Unique constraint: (requester_id, requestee_id, status) where status = 'pending'
```

### FAQs Table
```
faqs
├── id (bigint, primary key)
├── question (string)
├── answer (text)
├── order (integer, default: 0)
├── is_published (boolean, default: true)
├── created_at (timestamp)
└── updated_at (timestamp)
```

### Site Settings Table (Key-Value)
```
site_settings
├── id (bigint, primary key)
├── key (string, unique)
├── value (text, nullable)
├── created_at (timestamp)
└── updated_at (timestamp)
```

### Activity Log Table (for statistics)
```
activity_logs
├── id (bigint, primary key)
├── user_id (foreign key -> users.id, nullable)
├── action (string) -- 'login', 'register', 'profile_created', 'transfer_requested', etc.
├── description (string, nullable)
├── ip_address (string, nullable)
├── user_agent (string, nullable)
├── created_at (timestamp)
```

---

## Feature Specifications

### 1. Authentication System

#### 1.1 User Registration
- Username (unique, alphanumeric, 3-50 characters)
- Email (unique, valid format)
- Password (minimum 8 characters, requires complexity)
- Password confirmation
- On registration, send verification email with signed URL
- User cannot login until email is verified
- Show clear feedback on registration success

#### 1.2 Email Verification
- Generate signed verification URL valid for 24 hours
- Clicking link marks email_verified_at timestamp
- Option to resend verification email
- Rate limit resend to 1 per minute

#### 1.3 Login
- Accept username OR email for login
- Validate credentials
- Check email_verified_at is not null
- Redirect to profile creation if no profile exists
- Redirect to dashboard if profile complete
- Remember me functionality

#### 1.4 Password Reset
- Request reset via email
- Send signed reset link valid for 1 hour
- Allow setting new password
- Invalidate all existing sessions on password change

### 2. Profile Management

#### 2.1 Profile Creation Flow
**Step 1: Eligibility Check**
- User must confirm probation status
- If on probation, sick leave (without clearance), rehabilitation, or under investigation → show error, cannot proceed
- If probation completed → proceed to profile form

**Step 2: Profile Form**
Required fields:
- First name
- Last name
- Current employer (dropdown of ministries)
- Current region (dropdown)
- Current town (dropdown, filtered by region)
- Contact number (Namibian format validation)
- Email (pre-filled from registration)
- Preferred communication method

Optional fields:
- Employee number
- Job grade (dropdown or text)
- Alternative contact number
- Profile picture (max 2MB, jpg/png)

**Step 3: Preferred Transfer Locations**
- Select up to 3 preferred locations
- Each preference: Region + Town (optional)
- Priority order: 1st choice, 2nd choice, 3rd choice
- At least 1 preferred location required

#### 2.2 View Profile
- Display all profile information
- Show current transfer availability status
- Show number of pending transfer requests (incoming/outgoing)
- Link to edit profile

#### 2.3 Edit Profile
- All fields editable except probation status (requires admin)
- Profile picture upload/change/remove
- Update preferred locations
- Toggle availability for transfers (opt-out feature)

### 3. Transfer Matching System

#### 3.1 View Matched Transfers
Dashboard shows employees who:
- Are in one of your preferred locations (their current location)
- Have your current location in their preferred locations
- Are available for transfers
- Have completed profiles
- Probation completed

Display as cards or list with:
- Name (first name + last initial for privacy)
- Current location (Region, Town)
- Employer (Ministry name)
- Job grade (if provided)
- "View Details" button
- "Arrange Transfer" button

Sorting options:
- Best match (mutual match score)
- Newest profiles
- By region/town

#### 3.2 Search All Transfers
Search/filter interface for all available transfer candidates:
- Filter by region
- Filter by town
- Filter by employer/ministry
- Filter by job grade
- Search by keyword

Results show same card format as matched transfers

#### 3.3 View Transfer Candidate Details
Modal or page showing:
- Full name (first + last initial)
- Current employer
- Current location
- Job grade
- Preferred locations
- Profile picture (if uploaded)
- "Arrange Transfer" button
- Contact details: HIDDEN until transfer accepted

### 4. Transfer Request System

#### 4.1 Send Transfer Request
- Click "Arrange Transfer" on candidate card
- Optional: Add a message/note
- Confirm action
- System creates transfer_request record with status 'pending'
- System sends email notification to requestee
- Show success message

**Business Rules:**
- Cannot send request to same person if pending request exists
- Cannot send request if requester has opted out
- Cannot send request if requestee has opted out
- Limit: Maximum 5 pending outgoing requests at a time

#### 4.2 View Incoming Requests
List of transfer requests received:
- Requester name
- Requester current location
- Requester employer
- Request date
- Message (if any)
- Actions: Accept | Decline

#### 4.3 View Outgoing Requests
List of transfer requests sent:
- Requestee name
- Requestee current location
- Request date
- Status (pending/accepted/declined)
- Action: Cancel (if pending)

#### 4.4 Accept Transfer Request
- Click Accept on incoming request
- Update status to 'accepted'
- Set responded_at timestamp
- **REVEAL CONTACT DETAILS**: Both parties can now see each other's:
  - Full name
  - Email
  - Contact number
  - Alternative contact number
- Send email notification to requester
- Show success message with contact details

#### 4.5 Decline Transfer Request
- Click Decline on incoming request
- Update status to 'declined'
- Set responded_at timestamp
- Send email notification to requester (polite decline, no details)
- Request removed from active lists

#### 4.6 Cancel Transfer Request
- Requester can cancel their pending request
- Update status to 'cancelled'
- No notification sent
- Request removed from requestee's incoming list

#### 4.7 Opt-Out Feature
- Toggle on profile: "Available for transfers"
- When OFF:
  - User hidden from all search results
  - Cannot send new requests
  - Existing pending outgoing requests remain
  - Incoming requests still visible

### 5. Email Notifications

#### 5.1 Email Types
1. **Verification Email**
   - Subject: "Verify your Employee Transfer Portal account"
   - Contains verification link
   - Expires in 24 hours

2. **Welcome Email** (after verification)
   - Subject: "Welcome to the Employee Transfer Portal"
   - Brief guide on creating profile

3. **Transfer Request Received**
   - Subject: "New Transfer Request from [First Name]"
   - Brief details of requester
   - Link to view request on portal

4. **Transfer Request Accepted**
   - Subject: "Good news! [First Name] accepted your transfer request"
   - Contact details of acceptor
   - Next steps guidance

5. **Transfer Request Declined**
   - Subject: "Update on your transfer request"
   - Polite message, no requester details
   - Encouragement to keep looking

6. **Password Reset**
   - Subject: "Reset your password"
   - Reset link valid for 1 hour

### 6. Static Pages

#### 6.1 FAQ Page
- Accordion-style Q&A list
- Admin-manageable content
- Common questions about:
  - Eligibility requirements
  - How matching works
  - Privacy and data handling
  - Transfer process after matching

#### 6.2 About Us Page
- Portal description and purpose
- Contact information for support
- Admin-configurable content

#### 6.3 Home/Landing Page
- Hero section explaining the portal
- Key features/benefits
- Call-to-action: Register / Login
- Statistics (optional): X employees registered, Y transfers arranged

### 7. Admin Dashboard

#### 7.1 Statistics Overview
- Total registered users (with date filter)
- New registrations by day/week/month/year
- Total profiles created
- Active vs inactive users
- Transfer requests statistics:
  - Total sent
  - Acceptance rate
  - Pending requests
- User activity/visit logs

#### 7.2 User Management
- List all users with search/filter
- View user details
- Activate/deactivate users
- Reset user password
- Edit user probation status

#### 7.3 Content Management
- Manage FAQs (CRUD)
- Edit About Us page content
- Manage site settings

#### 7.4 Master Data Management
- Manage regions (CRUD)
- Manage towns (CRUD)
- Manage employers/ministries (CRUD)

---

## API Routes Structure

```
Public Routes (No Auth):
GET  /                          → Home page
GET  /about                     → About page
GET  /faq                       → FAQ page
GET  /login                     → Login form
POST /login                     → Process login
GET  /register                  → Registration form
POST /register                  → Process registration
GET  /verify-email/{id}/{hash}  → Verify email
POST /email/verification-notification → Resend verification
GET  /forgot-password           → Password reset request form
POST /forgot-password           → Send reset link
GET  /reset-password/{token}    → Password reset form
POST /reset-password            → Process password reset

Protected Routes (Auth Required):
POST /logout                    → Logout
GET  /dashboard                 → Main dashboard with matches
GET  /profile                   → View own profile
GET  /profile/create            → Profile creation form
POST /profile                   → Store new profile
GET  /profile/edit              → Edit profile form
PUT  /profile                   → Update profile
POST /profile/picture           → Upload profile picture
DELETE /profile/picture         → Remove profile picture

GET  /transfers                 → View matched transfers
GET  /transfers/search          → Search all transfers
GET  /transfers/{id}            → View transfer candidate details

GET  /requests                  → View all requests (tabs: incoming/outgoing)
GET  /requests/incoming         → Incoming requests list
GET  /requests/outgoing         → Outgoing requests list
POST /requests                  → Send new transfer request
PUT  /requests/{id}/accept      → Accept request
PUT  /requests/{id}/decline     → Decline request
DELETE /requests/{id}           → Cancel request

Admin Routes (Admin Auth Required):
GET  /admin/dashboard           → Admin statistics
GET  /admin/users               → User management
GET  /admin/users/{id}          → View user details
PUT  /admin/users/{id}          → Update user
GET  /admin/faqs                → FAQ management
POST /admin/faqs                → Create FAQ
PUT  /admin/faqs/{id}           → Update FAQ
DELETE /admin/faqs/{id}         → Delete FAQ
GET  /admin/regions             → Region management
GET  /admin/towns               → Town management
GET  /admin/employers           → Employer management
GET  /admin/settings            → Site settings
PUT  /admin/settings            → Update settings
```

---

## Implementation Phases

### Phase 1: Foundation (Week 1-2)
1. Laravel project setup with chosen frontend stack
2. Database migrations and seeders (regions, towns, employers)
3. User authentication with email verification
4. Basic layouts and navigation

### Phase 2: Profile System (Week 3-4)
1. Profile creation flow with eligibility check
2. Profile view and edit functionality
3. Profile picture upload
4. Preferred locations management

### Phase 3: Transfer Matching (Week 5-6)
1. Matching algorithm implementation
2. Matched transfers dashboard view
3. Search and filter functionality
4. Transfer candidate detail view

### Phase 4: Request System (Week 7-8)
1. Transfer request creation
2. Incoming/outgoing request views
3. Accept/decline/cancel functionality
4. Contact reveal on acceptance
5. Email notifications

### Phase 5: Admin & Polish (Week 9-10)
1. Admin dashboard with statistics
2. User management
3. Content management (FAQs, About)
4. Master data management
5. Testing and bug fixes

### Phase 6: Deployment (Week 11)
1. Production environment setup
2. SSL configuration
3. Email service configuration
4. Final testing
5. Go-live

---

## Seed Data Required

### Namibian Regions (14 regions)
```
Erongo, Hardap, Karas, Kavango East, Kavango West, Khomas, Kunene, 
Ohangwena, Omaheke, Omusati, Oshana, Oshikoto, Otjozondjupa, Zambezi
```

### Major Towns (Examples per region)
```
Khomas: Windhoek
Erongo: Walvis Bay, Swakopmund, Henties Bay, Omaruru, Karibib
Oshana: Oshakati, Ondangwa
Kavango East: Rundu
Zambezi: Katima Mulilo
Karas: Keetmanshoop, Lüderitz
Hardap: Mariental, Rehoboth
... (complete list of all major towns)
```

### Government Ministries/Employers
```
- Ministry of Education, Arts and Culture
- Ministry of Health and Social Services
- Ministry of Finance
- Ministry of Home Affairs, Immigration, Safety and Security
- Ministry of Works and Transport
- Ministry of Agriculture, Water and Land Reform
- Ministry of Justice
- Ministry of Labour, Industrial Relations and Employment Creation
- Ministry of Environment, Forestry and Tourism
- Office of the Prime Minister
- Office of the President
- ... (all government ministries and agencies)
- Other (for non-ministry employers)
```

---

## Security Considerations

1. **Authentication**: Use Laravel's built-in hashing, rate limiting on login attempts
2. **Authorization**: Gate/Policy checks for profile access, request operations
3. **Data Privacy**: Contact details hidden until transfer accepted
4. **Input Validation**: Form requests with validation rules
5. **CSRF Protection**: Laravel's default CSRF tokens
6. **XSS Prevention**: Blade's automatic escaping
7. **SQL Injection**: Eloquent ORM prevents SQL injection
8. **File Upload**: Validate file types, size limits, store outside public
9. **Rate Limiting**: Limit API calls, email sends, request creation

---

## Testing Checklist

### Authentication Tests
- [ ] User can register with valid data
- [ ] User cannot register with duplicate email/username
- [ ] Verification email is sent on registration
- [ ] User cannot login without email verification
- [ ] User can verify email via link
- [ ] User can login after verification
- [ ] Password reset flow works

### Profile Tests
- [ ] User on probation cannot create profile
- [ ] User can create profile with valid data
- [ ] Profile picture upload works
- [ ] User can edit profile
- [ ] Preferred locations limited to 3

### Transfer Tests
- [ ] Matched transfers show correct results
- [ ] Search filters work correctly
- [ ] User can send transfer request
- [ ] User cannot send duplicate pending request
- [ ] Requestee receives notification
- [ ] Accept reveals contact details
- [ ] Decline hides from requester
- [ ] Cancel removes pending request
- [ ] Opted-out users hidden from search

### Admin Tests
- [ ] Admin can view statistics
- [ ] Admin can manage users
- [ ] Admin can manage content
- [ ] Admin can manage master data

---

## Notes for Development

1. **Use Laravel Queues** for all email sending to prevent slow responses
2. **Implement soft deletes** on users and profiles for data retention
3. **Add indexes** on frequently queried columns (region_id, town_id, employer_id, status)
4. **Cache** regions, towns, and employers (they rarely change)
5. **Log activity** for audit trail and statistics
6. **Use Transactions** for multi-step operations (profile + locations)
7. **Implement search** with Laravel Scout if scaling needed
8. **Consider pagination** on all list views (10-20 items per page)

---

## UI/UX Recommendations

1. **Mobile-first design** - Many users will access via phone
2. **Clear progress indicators** for multi-step forms
3. **Toast notifications** for actions (request sent, accepted, etc.)
4. **Skeleton loaders** while data fetches
5. **Empty states** with helpful guidance
6. **Confirmation modals** for destructive actions
7. **Accessibility**: ARIA labels, keyboard navigation, color contrast
