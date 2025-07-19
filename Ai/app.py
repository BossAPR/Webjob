
from flask import Flask, jsonify
from flask_sqlalchemy import SQLAlchemy
import pandas as pd
import joblib  # สำหรับการบันทึกและโหลดโมเดล
from sklearn.ensemble import RandomForestRegressor

# สร้าง Flask app
app = Flask(__name__)

# การเชื่อมต่อกับฐานข้อมูล MySQL
app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+pymysql://root:@localhost/webjob'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

# สร้าง SQLAlchemy instance
db = SQLAlchemy(app)

# โมเดลสำหรับฐานข้อมูลผู้สมัคร
class Applicant(db.Model):
    __tablename__ = 'applicant'
    account_id = db.Column(db.Integer, primary_key=True)
    sex = db.Column(db.Integer)  # เปลี่ยนจาก gender เป็น sex
    old = db.Column(db.Integer)  # เปลี่ยนจาก age เป็น old
    qualification = db.Column(db.Integer)  # เปลี่ยนจาก education เป็น qualification
    course = db.Column(db.Integer)  # เปลี่ยนจาก major เป็น course
    experience = db.Column(db.Integer)

# โมเดลสำหรับฐานข้อมูลตำแหน่งงาน
class Job(db.Model):
    __tablename__ = 'job_ad'
    job_ad_id = db.Column(db.Integer, primary_key=True)
    job_name = db.Column(db.String(100))
    qualification = db.Column(db.Integer)  # เปลี่ยนจาก education เป็น qualification
    course = db.Column(db.Integer)  # เปลี่ยนจาก major เป็น course
    sex = db.Column(db.Integer)  # เปลี่ยนจาก gender เป็น sex
    age_min = db.Column(db.Integer)
    experience_min = db.Column(db.Integer)  # เปลี่ยนจาก experience_min เป็น exp_min

# โหลดโมเดลที่เทรนแล้ว (ไม่ต้องเทรนใหม่)
model = joblib.load('trained_model.pkl')

# ดึงข้อมูลผู้สมัครจากฐานข้อมูลและทำการทำนาย
@app.route('/predict/<int:applicant_id>', methods=['GET'])
def predict_suitability(applicant_id):
    # ดึงข้อมูลผู้สมัครจากฐานข้อมูลโดยใช้ app_id
    applicant = Applicant.query.filter_by(account_id=applicant_id).first()
    
    if not applicant:
        return jsonify({"error": "Applicant not found"}), 404

    # สร้าง DataFrame สำหรับผู้สมัครใหม่
    new_applicant = pd.DataFrame({
        'sex': [applicant.sex],           # เพศผู้สมัคร
        'old': [applicant.old],           # อายุผู้สมัคร
        'qualification': [applicant.qualification],  # การศึกษาผู้สมัคร
        'course': [applicant.course],     # สาขาผู้สมัคร
        'experience': [applicant.experience]  # ประสบการณ์ผู้สมัคร
    })

    # ดึงข้อมูลตำแหน่งงานจากฐานข้อมูล
    jobs = Job.query.all()
    
    if not jobs:
        return jsonify({"error": "No jobs available"}), 404

    # คำนวณความเหมาะสมในแต่ละตำแหน่งงาน
    suitability_scores = []

    for job in jobs:
        # สร้าง DataFrame สำหรับการทำนาย
        applicant_features = pd.DataFrame({
        'gender_applicant': [new_applicant['sex'][0]],     # เพศผู้สมัคร (เปลี่ยนชื่อเป็น gender_applicant)
        'age': [new_applicant['old'][0]],                   # อายุผู้สมัคร (เปลี่ยนชื่อเป็น age)
        'education_job': [job.qualification],               # ค่าการศึกษาจากตำแหน่งงาน (เปลี่ยนชื่อเป็น education_job)
        'major_job': [job.course],                           # ค่าสาขาจากตำแหน่งงาน (เปลี่ยนชื่อเป็น major_job)
        'experience': [new_applicant['experience'][0]],     # ประสบการณ์ผู้สมัคร
        'gender_job': [job.sex],                             # เพศของตำแหน่งงาน (เปลี่ยนชื่อเป็น gender_job)
        'age_min': [job.age_min],                           # อายุขั้นต่ำ
        'experience_min': [job.experience_min]              # ประสบการณ์ขั้นต่ำ
        })

        # ทำนายคะแนนความเหมาะสม
        score = model.predict(applicant_features)[0]  # ค่าความเหมาะสมที่ทำนาย
        suitability_scores.append({
        'job_ad_id': job.job_ad_id,         # ส่งค่า job_ad_id ด้วย
        'job_name': job.job_name,           # ชื่อของตำแหน่งงาน
        'suitability_score': score * 100    # แสดงคะแนนเป็นเปอร์เซ็นต์
    })

    # ส่งผลลัพธ์กลับไปที่ client
    return jsonify(suitability_scores)

if __name__ == '__main__':
    app.run(debug=True)
