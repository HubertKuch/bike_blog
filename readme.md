# API Reference

## News

### Get all news

Base url

```mysql
GET /api/v2/news/
```

Response

```json
[
  {
    "year": 2015,
    "news": [
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
        "id": "8aae5c3f-0e30-4196-b5db-34c36e249167",
        "title": "Test",
        "description": "test",
        "tags": [
          "test",
          "test12"
        ],
        "time": "2015-12-31"
      }
    ]
  },
  {
    "year": 2014,
    "news": [
      {
        "id": "2ec80a1e-d1ec-4db0-b4bd-398549c96d6e",
        "title": "Test",
        "description": "test",
        "tags": [
          "test",
          "Test2"
        ],
        "time": "2014-12-31"
      }
    ]
  },
  {
    "year": 2012,
    "news": [
      {
        "id": "c665aaea-a893-4051-8367-b3d13f4ab389",
        "title": "Test",
        "description": "test",
        "tags": [
          "test",
          "Test2"
        ],
        "time": "2012-04-12"
      }
    ]
  }
]
```
### Get news by id

Base url

```mysql
GET /api/v2/news/:uuid
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
GET /api/v2/news/tag/:tag
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
POST /api/v2/news/
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
