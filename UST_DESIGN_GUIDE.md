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
- **Headings**: Georgia (serif) - conveys formality and academic prestige
- **Body**: Segoe UI / Tahoma / Geneva / Verdana (sans-serif) - ensures readability

### Font Hierarchy
- **Large Titles**: 4xl, font-bold, Georgia serif
- **Section Headers**: 2xl-3xl, font-bold, Georgia serif
- **Subheadings**: xl, font-semibold, Georgia serif
- **Body Text**: sm-base, font-body, sans-serif
- **Labels**: sm, font-semibold, text color: dark gray

---

## Component Styling

### Buttons
- **Primary Button (UST Gold)**
  - Background: #F4C300
  - Hover: #D4A400
  - Text: Dark (#1A1A1A)
  - Padding: py-3 px-6
  - Border Radius: rounded-lg (minimal roundness)
  - Shadow: shadow-ust

- **Secondary Button (Border)**
  - Border: 2px solid #F4C300
  - Text: #F4C300
  - Background: white with hover effect (light gold background)
  - Hover: bg-ust-gold/5

- **Action Buttons**: Smaller variants for table actions
  - Edit: Gold background, white text
  - Delete: Red background, white text

### Input Fields
- Border: 2px solid gray-200
- Background: #FBF8F3 (cream)
- Focus State: border-ust-gold, ring-2 ring-ust-gold/20
- Border Radius: rounded-lg
- Padding: px-4 py-3

### Cards
- Background: White
- Border: 1px solid gray-100
- Shadow: shadow-ust (subtle)
- Border-top: 4px solid #F4C300 (optional accent)

### Navigation Bar
- Background: White
- Border-bottom: 4px solid #F4C300
- Items: Dark text with hover transition to gold

### Summary Cards
- Background: White
- Border-top: 4px solid transparent (changes to gold on hover)
- Smooth hover animation
- Icon indicators for each metric

### Tables
- Header Background: #F9F7F3 (light background)
- Header Border-bottom: 2px solid #F4C300
- Row Hover: bg-ust-cream/50
- Badges: Gold background with 10-20% opacity for role/year level display

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
- Full-height gradient background (light backgrounds)
- Centered card with white background
- Gold graduation cap icon in circular badge
- "School Events Tracker" heading with subtitle
- Email and password inputs with cream backgrounds
- "Forgot password?" link in gold
- Gold primary button with icon
- Divider line with "or" label
- Link to signup with border button style
- Academic yet modern aesthetic

### Sign Up Page
- Similar layout to login page
- Two-column input grid for name fields
- Year level dropdown selector
- Create Account button (gold)
- Link to login page

### Home Page (Landing)
- Header with logo and navigation
- Dark hero section with gradient (dark to gray)
- Three feature cards with icons:
  - Track Events (calendar icon)
  - Stay Informed (bell icon)
  - Easy Access (mobile icon)
- Announcement section with gold border
- Rich footer with multiple columns
- Icon usage throughout for visual interest

### Dashboard (Admin)
- Header with admin title
- Four summary cards showing metrics:
  - Total Users
  - Administrators
  - Regular Users
  - Organizers
- Create User panel with styled header
- User management table with:
  - Sortable columns
  - Role and year level badges
  - Edit and Delete action buttons
- Edit User modal with overlay
- Consistent gold accent borders

### Unauthorized Page
- Lock icon in red badge
- Clear denial message
- Gold "Return to Login" button
- Gold-bordered "Go to Home" link
- Auto-redirect message

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
  heading: ['Georgia', 'serif'],
  body: ['Segoe UI', 'Tahoma', 'Geneva', 'Verdana', 'sans-serif'],
}

boxShadow: {
  ust: '0 2px 8px rgba(26, 26, 26, 0.08)',
  ust-md: '0 4px 12px rgba(26, 26, 26, 0.12)',
}
```

---

## CSS Classes Used

### Common Utilities
- `font-heading` - Georgia serif for titles
- `font-body` - Sans-serif for content
- `bg-ust-gold` - Primary gold background
- `bg-ust-dark` - Dark background
- `bg-ust-light-bg` - Light page background
- `bg-ust-cream` - Cream input background
- `text-ust-dark` - Dark text
- `text-ust-gray` - Gray text
- `text-ust-gold` - Gold text
- `border-ust-gold` - Gold borders
- `shadow-ust` - Subtle shadow
- `shadow-ust-md` - Medium shadow

---

## Brand Characteristics

✨ **Academic & Prestigious**: Serif headings, formal color palette, institutional feel
✨ **Trustworthy**: Consistent spacing, clean layouts, clear hierarchy
✨ **Modern & Usable**: Smooth transitions, intuitive interactions, responsive design
✨ **Professional**: Minimal gradients, sophisticated color use, elegant typography

---

## Files Updated

1. **login.php** - UST-styled login form
2. **signup.php** - UST-styled registration form
3. **home.php** - UST-styled landing page with features
4. **dashboard.php** - UST-styled admin dashboard
5. **unauthorized.php** - UST-styled access denied page
6. **tailwind.config.js** - Extended with UST color palette and fonts

### Old Files Archived
- login_old.php
- signup_old.php
- home_old.php
- dashboard_old.php
- unauthorized_old.php

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

1. Add UST logo/emblem in header
2. Implement page transitions with smooth animations
3. Add dark mode toggle
4. Create toast notifications styled with UST colors
5. Add loading skeleton screens
6. Implement breadcrumb navigation
7. Add modal animations
8. Create custom form validation messages

---

## Support & Maintenance

The design is built using Tailwind CSS with custom extensions. To modify colors:
1. Update `tailwind.config.js` color values
2. Run Tailwind rebuild: `npm run build:css` (if applicable)
3. Update references in HTML files

For questions about the design system, refer to the official UST visual identity guidelines.

**Design Implementation Date**: April 29, 2026
**Design Version**: 1.0
