# API Reference

## News

### Get all news

Base url

```mysql
GET /api/v1/news/
```

Response

```json
[
  {
    "id": "1ec210a5-ee6e-4310-8288-3738c379a89b",
    "title": "Test",
    "description": "test",
    "tags": [
      "test",
      "Test2"
    ],
    "time": "2015-12-31"
  },
  {
    "id": "2ec80a1e-d1ec-4db0-b4bd-398549c96d6e",
    "title": "Test",
    "description": "test",
    "tags": [
      "test",
      "Test2"
    ],
    "time": "2015-12-31"
  }
]
```

### Get news by id

Base url

```mysql
GET /api/v1/news/:uuid
```

Response
Code

200:

```json
{
  "id": "1ec210a5-ee6e-4310-8288-3738c379a89b",
  "title": "Test",
  "description": "test",
  "tags": [
    "test",
    "Test2"
  ],
  "time": "2015-12-31"
}
```

404:

```json
{
  "message": "News with id $id not found."
}
```

### Get news by tag

```mysql
GET /api/v1/news/tag/:tag
```

Response
Code 200:

```json
[
  {
    "id": "1ec210a5-ee6e-4310-8288-3738c379a89b",
    "title": "Test",
    "description": "test",
    "tags": [
      "test",
      "Test2"
    ],
    "time": "2015-12-31"
  }
]
```

### Create news

```mysql
POST /api/v1/news/
```

Body

```json
{
  "title": "string",
  "description": "string",
  "tags": [
    "array",
    "of",
    "strings"
  ],
  "date": "YYYY-MM-DD"
}
```

Response code 200

```json
{
  "message": "Success"
}
```

400:

```json
{
  "message": "Invalid request"
}
```
