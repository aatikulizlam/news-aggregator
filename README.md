# News Aggregator Backend

## Description
This project implements the backend for a news aggregator website. It fetches articles from multiple news sources (e.g., NewsAPI, The Guardian, New York Times) via scheduled jobs and serves them from a local database to the frontend through API endpoints.

---

## Features

1. **Fetch Latest Articles**:
   - Scheduled jobs fetch the latest articles from supported news sources.
   - Articles are stored in a normalized format in the database.

2. **Serve Articles via API**:
   - Supports filtering by category, source, date, and pagination.
   - Retrieves data directly from the database.

3. **Supported News Sources**:
   - NewsAPI
   - The Guardian
   - New York Times

---

## Setup Instructions

### 1. Clone the Repository
```bash
git clone <repository-url>
cd <repository-name>
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Configure Environment
Copy the .env.example to .env and set the api keys
```env
# API Keys
NEWSAPI_KEY=your_newsapi_key_here
GUARDIAN_KEY=your_guardian_key_here
NYT_KEY=your_nyt_key_here
```

### 4. Run Migrations
Create the database and run migrations:
```bash
php artisan migrate
```

### 5. Set Up Scheduler
To run the Laravel scheduler, add the following cron job:
```bash
* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
```
Replace `/path-to-your-project` with the actual path to your Laravel application.

### 6. Run the Application
Start the Laravel development server:
```bash
php artisan serve
```

---

## API Endpoints

### **GET /articles**
Retrieve a list of articles.

#### Query Parameters:
| Parameter   | Type   | Description                        |
|-------------|--------|------------------------------------|
| `source`    | string | Filter by news source (optional).  |
| `category`  | string | Filter by category (optional).     |
| `date`      | string | Filter by publication date (optional, `YYYY-MM-DD`). |
| `perPage`   | int    | Number of results per page (optional, default: 10). |

#### Example Request:
```bash
GET /articles?source=The Guardian&category=tech&date=2025-01-21&perPage=5
```

#### Response Example:
```json
{
  "data": [
    {
      "title": "Example Article",
      "author": "John Doe",
      "source": "The Guardian",
      "url": "https://example.com/article",
      "content": "Some content here...",
      "category": "Tech",
      "published_at": "2025-01-21 13:00:00"
    }
  ],
  "current_page": 1,
  "total": 50
}
```

---

## Future Enhancements

1. **Caching**:
   - Implement caching for faster API responses.

2. **Webhooks**:
   - Integrate webhooks to notify users of new articles.

3. **Advanced Filtering**:
   - Add more granular filtering options like keywords or authors.

4. **Queue Implementation**:
   - Replace scheduler-only jobs with a queue system to improve scalability.
   - Use queued jobs to fetch articles asynchronously and handle retries for API failures.
