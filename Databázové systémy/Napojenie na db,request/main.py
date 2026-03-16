from flask import Flask
from dotenv import load_dotenv
import psycopg2
import json
import os
app =Flask(__name__)

app.config['JSON_SORT_KEYS'] = False

load_dotenv()
HOST=os.getenv("HOST")
DAT=os.getenv("DAT")
USER=os.getenv("USER")
PAS=os.getenv("PAS")
@app.route("/v1/health")



def v1():

    conn=psycopg2.connect(
        host=HOST,
        database=DAT,
        user=USER,
        password=PAS
    )

    cursor=conn.cursor()
    cursor.execute(
        "SELECT version();"
    )
    version=cursor.fetchall()
    cursor.execute(
        "SELECT pg_database_size('dota2')/1024/1024 as dota2_db_size;"
    )
    data=cursor.fetchall()

    version=str( version)
    data=str(data)
    version= version[2:len( version)-2]
    data=data[2:6]
    dump={
        'version':version,
        'dota2_db_size':data

    }
    dump={
        'pgsql':dump
    }

    cursor.close()
    conn.close()
    return dump

if __name__=="main":
    app.run(debug=False)
  