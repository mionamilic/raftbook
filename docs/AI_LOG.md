# AI Usage Log – RaftBook

## Milestone 1

### Tool Used

ChatGPT (OpenAI)

### Purpose

AI assistance was used during the initial frontend development and project planning.

### Areas of Assistance

- Explaining HTML, CSS, and JavaScript concepts.
- Suggesting the structure of the SPA.
- Helping implement navigation between sections.
- Helping implement dynamic tour details.
- Explaining JavaScript event listeners and DOM manipulation.
- Helping prepare initial project documentation.

### Problems Encountered

**Problem 1: Tour images did not change**

The tour details page initially displayed the rafting image for all tours.

**Solution:** Added a dynamic image element and used JavaScript `classList.remove()` and `classList.add()` to update the image based on the selected tour.

**Problem 2: Book Now button did not work**

The button initially had no JavaScript click handler.

**Solution:** Added an event listener that displays a message and navigates to the login page.

**Problem 3: CSS syntax errors

While simplifying the stylesheet, several CSS syntax
errors appeared because some selectors and braces
were missing.

**Solution:** Reviewed the errors in VS Code,
corrected the CSS rules, and tested the website
again in the browser.

**Problem 4: Mobile navigation layout

The navigation links did not fit correctly on
smaller mobile screens.

**Solution:** Adjusted the responsive CSS rules
and tested the layout using browser developer tools.


### Verification

The tour detail pages and Book Now navigation were tested in the browser. The Dashboard preview was also displayed successfully.

### Notes

AI suggestions were used as development assistance. The implementation was reviewed and tested during development. Backend features are not yet implemented.
