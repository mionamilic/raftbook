# RaftBook – Development Guidelines

## Project Overview

RaftBook is a tourism booking web application for rafting, hiking, and canyoning adventures.

## Project Organization

- `frontend/` contains HTML, CSS, and JavaScript.
- `backend/routes/` will contain REST API endpoints.
- `backend/services/` will contain application business logic.
- `backend/dao/` will contain database access code.
- `backend/middleware/` will contain authentication and other middleware.
- `backend/config/` will contain application configuration.
- `backend/logs/` is reserved for application logs.
- `docs/` contains project documentation.

## Development Guidelines

- Use clear and understandable code.
- Keep HTML, CSS, and JavaScript in separate files.
- Use meaningful names for variables and functions.
- Maintain responsive design.
- Validate user input when implementing backend features.
- Never commit passwords or secret configuration files.
- Test changes before committing them.

## AI Assistance Guidelines

AI tools may be used to explain concepts, suggest code, and help identify errors.

All generated or suggested code should be reviewed, understood, and tested before being added to the project.

Significant AI assistance and actual errors encountered during development should be documented in `docs/AI_LOG.md`.

## Current Status

Milestone 1 focuses on the static SPA frontend, initial project structure, and database planning.

## Milestone 2 – Development Notes

The project was extended with a MySQL database
containing five related entities.

A reusable BaseDao class was implemented for
common database operations.

Five DAO classes were created:
UserDAO, CategoryDAO, TourDAO, BookingDAO
and ReviewDAO.

PHP routes were added for database access
and CRUD operations.

Database credentials were moved to a local
configuration file excluded from Git.

AI assistance was used for explaining PHP,
DAO architecture, prepared statements,
database integration and code organization.

All generated code should be reviewed and
tested before submission.
