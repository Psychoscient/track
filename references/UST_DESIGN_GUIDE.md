# UST-Inspired School Events Tracker - Design Implementation Guide

## Overview
The School Events Tracker application has been successfully redesigned with the official visual identity of the University of Santo Tomas (UST). The new design reflects a clean, academic, and prestigious aesthetic while maintaining modern usability and intuitive navigation.

---

## Color Palette

### Primary Colors
- **UST Gold (#F4C300)**: Primary accent color for buttons, highlights, and active states
  - Dark variant: #D4A400 (hover/active states)
  - Light variant: #FED766 (subtle highlights)

- **Dark Gray/Black (#1A1A1A)**: Primary text and headings
- **Dark Gray (#333333)**: Secondary text

### Background Colors
- **Light Background (#F9F7F3)**: Page backgrounds
- **Cream (#FBF8F3)**: Input fields and subtle accents
- **White (#FFFFFF)**: Cards and main content areas

---

## Typography

### Font Families
- **Headings**: Outfit (sans-serif) - modern, clean, and professional aesthetic
- **Body**: Segoe UI / Tahoma / Geneva / Verdana (sans-serif) - ensures readability

### Font Hierarchy
- **Large Titles**: 4xl, font-bold, Outfit
- **Section Headers**: 2xl-3xl, font-bold, Outfit
- **Subheadings**: xl, font-semibold, Outfit
- **Body Text**: sm-base, font-body, sans-serif
- **Labels**: sm, font-semibold, text color: dark gray

---

## Component Styling

### Buttons
- **Primary Button (UST Gold)**
  - Class: `bg-ust-gold hover:bg-ust-gold-dark text-ust-dark`
  - Padding: `py-3 px-6` (large) or `py-2 px-4` (small)
  - Border Radius: `rounded-lg`
  - Shadow: `shadow-ust`
  - Font: `font-semibold`
  - Icon: Typically paired with Font Awesome icon
  - Transition: `transition duration-200`

- **Secondary Button (Border)**
  - Class: `border-2 border-ust-gold text-ust-gold hover:bg-ust-gold/5`
  - Font: `font-semibold`
  - Padding: `py-2 px-6`
  - Background: white with light gold hover effect

- **Action Buttons**: Smaller variants for table actions
  - Edit: `bg-ust-gold text-white` with icon
  - Delete: `bg-red-600 text-white` with icon

### Input Fields
- Border: `border-2 border-gray-200`
- Background: `bg-ust-cream`
- Focus State: `focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20`
- Border Radius: `rounded-lg`
- Padding: `px-4 py-3`
- Text: `text-ust-dark`
- Placeholder: `placeholder-gray-400`
- Class: `input-field`

### Cards
- Background: `bg-white`
- Border: `border border-gray-100`
- Shadow: `shadow-ust`
- Border-top: `border-t-4 border-ust-gold` (signature accent)
- Border Radius: `rounded-lg`
- Padding: `p-6` to `p-8`

### Navigation Bar / Header
- Background: `bg-white`
- Border-bottom: `border-b-4 border-ust-gold`
- Shadow: `shadow-ust`
- Logo area: `w-10 h-10 bg-ust-gold rounded-lg flex items-center justify-center`
- Items: `text-ust-dark hover:text-ust-gold font-medium transition`
- Title: `text-2xl font-heading font-bold text-ust-dark`

### Summary/Dashboard Cards
- Background: `bg-white`
- Border: `border border-gray-100`
- Shadow: `shadow-ust`
- Border-top: `border-t-4 border-transparent` with CSS transition to gold on hover
- Hover effect: `-translate-y-0.5` (slight upward movement)
- Icons: Font Awesome solid icons in gold
- Text: Centered with large bold numbers

### Tables
- Header Background: `bg-ust-light-bg`
- Header Border-bottom: `border-b-2 border-ust-gold`
- Header Font: `font-semibold text-ust-dark`
- Row Hover: `hover:bg-ust-cream/50`
- Badges: `bg-ust-gold/20 text-ust-gold font-semibold px-3 py-1 rounded-full text-xs`

---

## Design Elements

### Rounded Corners
- Inputs: rounded-lg (minimal roundness)
- Buttons: rounded-lg
- Cards: rounded-lg
- No overly bubbly corners - maintains academic feel

### Shadows
- **Subtle Shadow (shadow-ust)**: 0 2px 8px rgba(26, 26, 26, 0.08)
- **Medium Shadow (shadow-ust-md)**: 0 4px 12px rgba(26, 26, 26, 0.12)

### Icons
- **Source**: Font Awesome 6.4.0
- **Style**: Solid icons for consistency
- **Color**: Match surrounding text or gold for emphasis
- **Usage**: Navigation, buttons, labels, and list items

### Spacing
- Generous whitespace for breathing room
- Section padding: py-16 for major sections
- Component gaps: gap-4 to gap-8
- Typography margins: mb-2 to mb-4 between elements

---

## Page-Specific Design

### Login Page
- Full-height layout: `min-h-screen flex flex-col`
- Background: Gradient `bg-gradient-to-br from-ust-light-bg via-ust-cream to-white`
- Main content: Centered flex container `grow flex items-center justify-center`
- Header section: Centered with UST gold circular badge containing graduation cap icon
- Logo area: `w-16 h-16 bg-ust-gold rounded-full flex items-center justify-center`
- Card container: `bg-white shadow-ust-md rounded-lg p-8 border border-gray-100`
- Form inputs:
  - Email and password with `bg-ust-cream` background
  - `border-2 border-gray-200` with gold focus states
  - Placeholder text in `placeholder-gray-400`
- "Forgot password?" link: `text-ust-gold hover:text-ust-gold-dark font-medium`
- Submit button: Gold background with icon
- Divider: `border-t border-gray-300` with "or" label centered
- Create Account link: Border style with gold text
- Footer: `text-center py-6 border-t border-ust-gold/10 bg-white/70 backdrop-blur-sm`

### Forgot Password Page
- Layout: Same centered card structure as login page
- Heading: "Reset Password" with subtitle explaining the action
- Single email input field with same styling as login
- Submit button: "Send Reset Link" with paper-plane icon
- Footer section: Same as login page

### Sign Up Page
- Similar layout to login page with gradient background
- Two-column input grid for name fields: `grid grid-cols-1 md:grid-cols-2 gap-5`
- Year level dropdown with cream background
- Password input with validation feedback
- Create Account button (gold)
- Link to login page with border style

### Home Page (Landing)
- Header: `bg-white shadow-ust border-b-4 border-ust-gold`
  - Logo: `w-10 h-10 bg-ust-gold rounded-lg`
  - Title: `text-2xl font-heading font-bold`
  - Navigation: Hidden on mobile, visible on md breakpoint
  - Logout button: Gold background
- Hero section: `bg-gradient-to-r from-ust-dark to-ust-gray text-white py-20`
  - Large heading: `text-5xl font-heading font-bold`
  - Subtitle: `text-lg text-gray-200`
  - Two buttons: Gold primary button and white border secondary button
- Features section: `max-w-7xl mx-auto px-6 py-16`
  - Grid: `grid md:grid-cols-3 gap-8`
  - Feature cards: White background with `border-t-4 border-ust-gold`
  - Each card has icon in gold circle: `w-12 h-12 bg-ust-gold/10 rounded-lg`
- Announcement section: `bg-white py-16 border-y-4 border-ust-gold`
  - Badge: `px-4 py-2 bg-ust-gold/10 rounded-full`
  - CTA button: Gold background
- Footer: `bg-ust-dark text-white py-8`
  - Grid layout: `grid md:grid-cols-3 gap-8`
  - Links hover to gold: `hover:text-ust-gold transition`
  - Logo same style as header

### Dashboard (Admin)
- Header: Same as home page
- Summary cards section:
  - Grid: `grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6`
  - Card styling: `bg-white rounded-lg shadow-ust p-6` with `border-t-4 border-ust-gold` on hover
  - Large number: `text-4xl font-heading font-bold text-ust-gold`
  - Label: `text-sm text-ust-gray font-semibold` with Font Awesome icon
  - CSS hover animation: Smooth transition with `-translate-y-2` effect
- Create User panel:
  - Header: `bg-gradient-to-r from-ust-dark to-ust-gray px-6 py-4 border-b-4 border-ust-gold`
  - Title: `text-lg font-heading font-bold text-white` with icon
  - Form grid: `grid grid-cols-1 md:grid-cols-2 gap-5`
  - Submit button: Gold with icon, right-aligned
- Users table:
  - Container: `bg-white shadow-ust rounded-lg overflow-hidden border border-gray-100`
  - Header: Same gradient as create user panel with gold border
  - Table header: `bg-ust-light-bg border-b-2 border-ust-gold`
  - Row hover: Light gold background
  - Action buttons: Small edit (gold) and delete (red) buttons with icons
- Edit modal:
  - Overlay: `fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center`
  - Card: `bg-white rounded-lg shadow-ust-md max-w-2xl` with `border-t-4 border-ust-gold`
  - Header: Same gradient with close button
  - Form: Similar structure to create user form

### Unauthorized Page
- Background: `bg-gradient-to-br from-ust-light-bg via-ust-cream to-white`
- Layout: Centered card `bg-white shadow-ust-md rounded-lg p-8 max-w-md`
- Lock icon: Red circle badge `w-16 h-16 bg-red-100 rounded-full` with red lock icon
- Heading: `text-3xl font-heading font-bold text-ust-dark`
- Message: `text-ust-gray mb-6 text-sm`
- Buttons:
  - Primary: "Return to Login" - gold background
  - Secondary: "Go to Home" - gold border (conditionally shown)
- Auto-redirect message: `text-xs text-ust-gray mt-6`

---

## Tailwind Configuration

Added custom colors and utilities to `tailwind.config.js`:

```javascript
colors: {
  ust: {
    gold: '#F4C300',
    gold-dark: '#D4A400',
    gold-light: '#FED766',
    dark: '#1A1A1A',
    gray: '#333333',
    light-bg: '#F9F7F3',
    cream: '#FBF8F3',
  },
}

fontFamily: {
  heading: ['Outfit', 'sans-serif'],
  body: ['Segoe UI', 'Tahoma', 'Geneva', 'Verdana', 'sans-serif'],
}

boxShadow: {
  ust: '0 2px 8px rgba(26, 26, 26, 0.08)',
  ust-md: '0 4px 12px rgba(26, 26, 26, 0.12)',
}
```

---

## CSS Classes Used

### Button Classes
- `bg-ust-gold hover:bg-ust-gold-dark text-ust-dark font-semibold py-3 rounded-lg shadow-ust transition` - Primary button
- `border-2 border-ust-gold text-ust-gold hover:bg-ust-gold/5 font-semibold rounded-lg transition` - Secondary button
- `px-4 py-2 font-semibold transition` - Small action buttons

### Input Classes
- `input-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream` - Standard form input

### Card Classes
- `bg-white shadow-ust rounded-lg p-8 border border-gray-100` - Standard card
- `bg-white shadow-ust rounded-lg p-8 border border-gray-100` with `border-t-4 border-ust-gold` - Accent card
- `summary-card bg-white rounded-lg shadow-ust p-6 flex flex-col items-center justify-center cursor-pointer border border-gray-100` - Dashboard summary card (with CSS for hover animation)

### Header Classes
- `bg-white shadow-ust border-b-4 border-ust-gold` - Main header
- `max-w-7xl mx-auto px-6 py-4 flex items-center justify-between` - Header container
- `w-10 h-10 bg-ust-gold rounded-lg flex items-center justify-center` - Logo container
- `text-2xl font-heading font-bold text-ust-dark` - Header title

### Layout Classes
- `min-h-screen flex flex-col font-body bg-gradient-to-br from-ust-light-bg via-ust-cream to-white` - Full page with gradient
- `grow flex items-center justify-center px-4 py-10` - Centered content area
- `max-w-7xl mx-auto px-6 py-16` - Content container
- `grid md:grid-cols-3 gap-8` - Feature grid (3 columns on medium+)
- `grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6` - Dashboard summary grid

### Text Classes
- `font-heading` - Outfit for titles (use with `font-bold`, `font-semibold`)
- `font-body` - Sans-serif for body text
- `text-ust-dark` - Primary dark text (#1A1A1A)
- `text-ust-gray` - Secondary gray text (#333333)
- `text-ust-gold` - Gold accent text (#F4C300)

### Background Classes
- `bg-ust-gold` - Primary gold background
- `bg-ust-dark` - Dark background
- `bg-ust-light-bg` - Light page background (#F9F7F3)
- `bg-ust-cream` - Cream background (#FBF8F3)

### Border & Shadow Classes
- `border-ust-gold` - Gold borders
- `border-t-4 border-ust-gold` - Top gold border (signature accent)
- `border-b-4 border-ust-gold` - Bottom gold border (headers)
- `shadow-ust` - Subtle shadow (0 2px 8px rgba(26, 26, 26, 0.08))
- `shadow-ust-md` - Medium shadow (0 4px 12px rgba(26, 26, 26, 0.12))

---

## Common Implementation Patterns

### Form Layouts
- Centered card containers on login/auth pages with max-width of 448px (`max-w-md`)
- Two-column grids for multi-field forms: `grid grid-cols-1 md:grid-cols-2 gap-5`
- All inputs use consistent styling with cream background
- Labels: `text-sm font-semibold text-ust-dark mb-2`
- Form spacing: `space-y-6` between form groups

### Header/Navigation Pattern
- Fixed structure: Logo + Title on left, Navigation + Logout on right
- Logo is 40x40 gold rounded square with icon
- Title uses Outfit with bold weight
- Navigation items respond to screen size (hidden on mobile with `hidden md:flex`)
- All headers have gold bottom border (4px)

### Card Hover Effects
- Summary cards: CSS class with smooth transform on hover
- Feature cards: Subtle shadow increase and upward movement
- All cards maintain 4px gold top border or transition to it on hover

### Modal/Dialog Pattern
- Overlay: `fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center`
- Modal: `max-w-2xl w-full` with gold top border
- Header: Gradient background with gold border
- Close button in top right
- Sticky header on mobile with `max-h-[90vh] overflow-y-auto`

### Typography Hierarchy
- Page titles: 4xl-5xl with Outfit
- Section headers: 2xl-3xl with Outfit
- Card titles: lg-xl with Outfit
- Body text: sm-base with sans-serif
- Small text: xs-sm for labels and metadata

### Icon Usage
- Font Awesome 6.4.0 solid icons throughout
- Icons paired with text labels in buttons: `<i class="fas fa-icon mr-2"></i>Text`
- Icons in circular containers for headers/badges
- Gold icons in feature cards: `text-ust-gold`

### Spacing Conventions
- Page padding: `px-6` for horizontal, `py-16` for vertical sections
- Card padding: `p-6` to `p-8` depending on card type
- Component gaps: `gap-4` to `gap-8` depending on layout
- Form groups: `space-y-6`
- Form fields: `space-y-5` or `gap-5`

### Color Application
- Primary actions: Always gold background
- Secondary actions: Gold border with transparent background
- Danger actions: Red background (delete buttons)
- Hover states: Darker shade of primary color
- Text: Dark gray for primary, lighter gray for secondary
- Focus states: Gold border with light gold ring

### Responsive Breakpoints
- Mobile-first approach with Tailwind breakpoints
- `hidden md:flex` for desktop-only navigation
- `md:grid-cols-2`, `md:grid-cols-3`, `md:grid-cols-4` for grids
- `sm:flex-row` for stacked layouts becoming horizontal
- Touch-friendly button sizes maintained on all breakpoints

---

## Brand Characteristics

✨ **Academic & Prestigious**: Serif headings, formal color palette, institutional feel
✨ **Trustworthy**: Consistent spacing, clean layouts, clear hierarchy
✨ **Modern & Usable**: Smooth transitions, intuitive interactions, responsive design
✨ **Professional**: Minimal gradients, sophisticated color use, elegant typography

---

## Files Updated

The following files have been implemented with the UST design system:

1. **login.php** - UST-styled login form with centered card, gradient background
2. **signup.php** - UST-styled registration form with same layout structure
3. **forgot-password.php** - Password reset request form, consistent with login/signup
4. **home.php** - UST-styled landing/dashboard page with hero section, feature cards, footer
5. **dashboard.php** - Admin dashboard with summary cards, user management table, edit modal
6. **unauthorized.php** - Access denied page with lock icon and action buttons
7. **controllers/controller.php** - Email templates redesigned with UST gold theme (password reset email)
8. **public/output.css** - Tailwind compiled CSS with UST custom classes
9. **tailwind.config.js** - Extended with UST color palette and typography

### Email Templates Implemented
- **Password Reset Email** - UST-themed with gold accents, Outfit headings, professional styling

---

## Implementation Status

### Fully Implemented ✅
- All authentication pages (login, signup, forgot-password) with consistent styling
- Admin dashboard with summary cards and user management
- Home/landing page with hero section and feature cards
- Unauthorized access page
- Email templates (password reset)
- All Tailwind custom classes and utilities
- Icon integration throughout (Font Awesome 6.4.0)
- Responsive grid layouts
- Form validation styling
- Modal dialogs for admin operations
- Navbar with responsive navigation

### Design Features Implemented
- Gradient backgrounds on appropriate pages
- Gold top/bottom borders on cards and headers
- Hover animations and transitions
- Summary card hover effects
- Consistent spacing and padding
- Typography hierarchy with Outfit headings
- Input focus states with gold styling
- Button variants (primary, secondary, danger)
- Table styling with hover effects
- Card shadows and depth
- Mobile-responsive design throughout

---

## Implementation Notes

### Responsive Design
- All pages are fully responsive
- Mobile-first approach
- Grid layouts adapt from 1 to 4 columns
- Touch-friendly button sizes

### Accessibility
- Semantic HTML structure
- Clear focus states on inputs
- Icon + text labels for clarity
- Good contrast ratios maintained

### Browser Compatibility
- Modern browsers supported
- Fallback fonts specified
- CSS custom properties used (via Tailwind)

### Performance
- Optimized class names
- Minimal custom CSS
- Leverages Tailwind's purging
- Font Awesome icons via CDN

---

## Next Steps (Optional Enhancements)

1. ✅ Add UST logo/emblem in header - **Consider adding**
2. Add page transitions with smooth animations
3. Add dark mode toggle
4. Create toast notifications styled with UST colors (currently using SweetAlert2)
5. Add loading skeleton screens for data-heavy pages
6. Implement breadcrumb navigation for admin dashboard
7. Add modal entrance animations
8. Create custom form validation messages with UST styling
9. Add loading spinner styled with gold
10. Implement image lazy loading with placeholder

---

## Version History

**Design Implementation Date**: April 29, 2026
**Design Version**: 1.0
**Last Updated**: May 1, 2026
**Current Status**: Fully Implemented and Documented

---

## Support & Maintenance

### Modifying the Design System

The design is built using Tailwind CSS with custom extensions defined in `tailwind.config.js`. To modify colors or add new utilities:

1. **Update Colors**:
   - Edit `tailwind.config.js` color values in the `colors.ust` object
   - Run Tailwind rebuild if using build process
   - Update references in HTML files

2. **Add New Components**:
   - Follow the existing Tailwind class patterns
   - Use custom utilities like `shadow-ust` consistently
   - Maintain the gold top/bottom border accent pattern

3. **Modify Spacing**:
   - Adjust padding/margin using Tailwind scale (p-6, p-8, etc.)
   - Keep section padding consistent at `py-16`
   - Maintain form spacing with `space-y-5` or `space-y-6`

### Best Practices for Consistency

- Always use `font-heading` (Outfit) for titles and headings
- Apply `shadow-ust` to all cards for consistency
- Use gold (`#F4C300`) only for primary actions and accents
- Maintain `border-t-4 border-ust-gold` on main content cards
- Keep button padding consistent: `py-3 px-6` for large, `py-2 px-4` for small
- Apply focus states: `focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20` to all inputs

### Testing Guidelines

When implementing new features:
1. Test on mobile (375px), tablet (768px), and desktop (1024px+) widths
2. Verify all hover states work smoothly
3. Check focus states for keyboard navigation
4. Test with different content lengths (short and long text)
5. Verify color contrast meets accessibility standards
6. Test all form inputs and buttons

### File Organization

- **Views**: All PHP pages in `/views/` directory
- **Styles**: Compiled CSS in `/public/output.css`
- **Config**: Tailwind config in `tailwind.config.js`
- **Scripts**: JavaScript functionality in `/script/` directory
- **Controllers**: Backend logic in `/controllers/` directory

For questions about the design system or implementation details, refer to this guide or review existing page implementations for patterns.
**Last Updated**: May 1, 2026
**Current Status**: Fully Implemented and Documented
