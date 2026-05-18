# User Table Adjustment

## Summary
Refine the user management table after the compact-layout update by shortening the first column header and restoring readable timestamps in the date columns.

## Changes
- Change the first user table header from `User ID` to `ID`.
- Show both `Created` and `Updated` values with date and time.
- Use timestamp format `M j, Y g:i A` so values appear like `May 18, 2026 9:42 PM`.
- Keep the current compact table styling, search, pagination, and icon-only actions unchanged.

## Test Plan
- Verify the first user table header displays `ID`.
- Verify both `Created` and `Updated` show date plus time for populated rows.
- Confirm the wider timestamp text still fits within the compact layout without breaking row actions.

## Assumptions
- This change applies only to the user management table.
- The agreed timestamp format is `M j, Y g:i A`.
