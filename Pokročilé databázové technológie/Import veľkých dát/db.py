from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker
from models import Base  # import your models file

# Adjust for your Docker PostgreSQL
DATABASE_URL = "postgresql+psycopg2://postgres:postgres@localhost:5000/mydb"

engine = create_engine(DATABASE_URL, echo=False)

SessionLocal = sessionmaker(bind=engine)
