from django.core.management.base import BaseCommand

from article.models import Article


SAMPLE_ARTICLES = [
    {
        "title": "Introduction to PostgreSQL",
        "content": "PostgreSQL is a powerful, open source object-relational database system. It has a strong reputation for reliability, feature robustness, and performance.",
        "author": "John Doe",
    },
    {
        "title": "Django Web Framework Guide",
        "content": "Django is a high-level Python web framework that encourages rapid development and clean, pragmatic design. Built by experienced developers, it takes care of much of the hassle of web development.",
        "author": "Jane Smith",
    },
    {
        "title": "Full-Text Search Explained",
        "content": "Full-text search refers to techniques for searching a single computer-stored document or a collection in a full-text database. It enables searching for documents based on content rather than metadata.",
        "author": "Bob Wilson",
    },
    {
        "title": "BM25 Ranking Algorithm",
        "content": "BM25 is a ranking function used by search engines to estimate the relevance of documents to a given search query. It is based on the probabilistic retrieval framework.",
        "author": "Alice Brown",
    },
    {
        "title": "Python Programming Basics",
        "content": "Python is a high-level, interpreted programming language known for its clear syntax and readability. It supports multiple programming paradigms including procedural, object-oriented, and functional programming.",
        "author": "Charlie Davis",
    },
    {
        "title": "Database Indexing Strategies",
        "content": "Database indexing is a data structure technique to efficiently retrieve records from the database files. Creating the right indexes on your database tables can dramatically improve query performance.",
        "author": "Diana Miller",
    },
    {
        "title": "REST API Design Best Practices",
        "content": "REST (Representational State Transfer) is an architectural style for designing networked applications. Good API design is crucial for creating maintainable and scalable web services.",
        "author": "Eve Johnson",
    },
    {
        "title": "Machine Learning Fundamentals",
        "content": "Machine learning is a subset of artificial intelligence that enables systems to learn and improve from experience without being explicitly programmed. It focuses on developing algorithms that can access data and use it to learn for themselves.",
        "author": "Frank White",
    },
]


class Command(BaseCommand):
    help = "Seed the database with sample articles"

    def handle(self, *args, **options):
        self.stdout.write("Deleting existing articles...")
        Article.objects.all().delete()

        self.stdout.write("Creating sample articles...")
        for article_data in SAMPLE_ARTICLES:
            Article.objects.create(**article_data)
            self.stdout.write(f"  Created: {article_data['title']}")

        self.stdout.write(
            self.style.SUCCESS(f"Successfully created {len(SAMPLE_ARTICLES)} articles")
        )
