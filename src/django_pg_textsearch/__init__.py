"""
django-pg-textsearch: Django integration for pg_textsearch BM25 full-text search.

Requires PostgreSQL 17+ with pg_textsearch extension.
https://github.com/timescale/pg_textsearch

Usage:
    from django_pg_textsearch import BM25Index, BM25Searchable

    class Article(BM25Searchable, models.Model):
        content = models.TextField()

        class Meta:
            indexes = [
                BM25Index(fields=['content'], name='article_bm25_idx'),
            ]

    # BM25 search (scores are NEGATIVE, lower = better)
    Article.search('postgresql')
    Article.search('django').filter(published=True)[:10]
"""

__version__ = "0.1.0"

from .checks import get_postgresql_version, is_pg_textsearch_available
from .expressions import BM25Match, BM25Query, BM25Score
from .indexes import BM25Index
from .mixins import BM25Searchable
from .operations import CreateBM25Index, CreateExtension, CreatePgTextsearchExtension
from .search import BM25SearchQuerySet

__all__ = [
    "__version__",
    # Mixin
    "BM25Searchable",
    # Index
    "BM25Index",
    # Search
    "BM25SearchQuerySet",
    # Expressions
    "BM25Match",
    "BM25Query",
    "BM25Score",
    # Operations
    "CreateBM25Index",
    "CreateExtension",
    "CreatePgTextsearchExtension",
    # Checks
    "get_postgresql_version",
    "is_pg_textsearch_available",
]

default_app_config = "django_pg_textsearch.apps.DjangoPgTextsearchConfig"
