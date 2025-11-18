from db import engine
from models import Base

# This will create all tables if they don’t exist
print("Creating tables...")
Base.metadata.create_all(bind=engine)
print("Done!")
