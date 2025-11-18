from db import engine
from models import Base

print("Dropping all tables...")

# Drop all tables defined in your models
Base.metadata.drop_all(bind=engine)

print("All tables dropped!")
