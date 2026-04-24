from reportlab.lib.pagesizes import letter
from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, PageBreak
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib import colors
from reportlab.lib.units import inch

def generate_pdf():
    doc = SimpleDocTemplate("Dokumentasi_Fitur_SD1Passo.pdf", pagesize=letter)
    styles = getSampleStyleSheet()
    
    # Custom styles
    title_style = styles['Title']
    heading_style = styles['Heading2']
    normal_style = styles['Normal']
    
    story = []

    # Title
    story.append(Paragraph("Dokumentasi Cara Kerja Fitur & Relasi Pengguna", title_style))
    story.append(Paragraph("Sistem Informasi Akademik SD 1 Passo", styles['Heading3']))
    story.append(Spacer(1, 0.5 * inch))

    # Section 1: Peran Pengguna
    story.append(Paragraph("1. Peran Pengguna (User Roles)", heading_style))
    story.append(Spacer(1, 0.1 * inch))
    
    roles = [
        ["Peran", "Deskripsi Singkat"],
        ["Administrator", "Pengelola utama sistem yang memiliki akses penuh ke modul akademik, konten website, dan pengaturan sistem."],
        ["Orang Tua", "Wali murid yang dapat memantau perkembangan akademik anak melalui portal khusus (Dashboard Orang Tua)."],
        ["Calon Siswa/Wali", "Masyarakat yang ingin mendaftarkan anak ke sekolah melalui formulir pendaftaran online."],
        ["Pengunjung Umum", "Masyarakat yang mengakses website untuk mendapatkan informasi profil, berita, dan galeri sekolah."]
    ]
    
    t = Table(roles, colWidths=[1.5*inch, 4.5*inch])
    t.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, 0), colors.blue),
        ('TEXTCOLOR', (0, 0), (-1, 0), colors.whitesmoke),
        ('ALIGN', (0, 0), (-1, -1), 'LEFT'),
        ('FONTNAME', (0, 0), (-1, 0), 'Helvetica-Bold'),
        ('BOTTOMPADDING', (0, 0), (-1, 0), 12),
        ('BACKGROUND', (0, 1), (-1, -1), colors.beige),
        ('GRID', (0, 0), (-1, -1), 1, colors.black)
    ]))
    story.append(t)
    story.append(Spacer(1, 0.3 * inch))

    # Section 2: Cara Kerja Fitur
    story.append(Paragraph("2. Cara Kerja Fitur Utama", heading_style))
    
    features = [
        ("Manajemen Konten (Admin)", "Admin dapat menambah, mengubah, dan menghapus Berita serta Galeri yang akan tampil di halaman utama website."),
        ("Pendaftaran Online (Calon Siswa)", "Calon siswa mengisi formulir di menu Pendaftaran. Data masuk ke Admin untuk divalidasi."),
        ("Pengolahan Data Siswa (Admin)", "Admin mengelola data siswa yang aktif, termasuk pembagian kelas dan penugasan guru."),
        ("Monitoring Akademik (Admin & Ortu)", "Guru/Admin menginput Nilai, Presensi, dan Catatan Perkembangan. Data ini secara otomatis muncul di Dashboard Orang Tua."),
        ("Jadwal Pelajaran", "Admin mengatur jadwal per mata pelajaran untuk setiap kelas agar dapat dipantau oleh Orang Tua.")
    ]
    
    for title, desc in features:
        story.append(Paragraph(f"<b>{title}</b>", normal_style))
        story.append(Paragraph(desc, normal_style))
        story.append(Spacer(1, 0.1 * inch))

    story.append(PageBreak())

    # Section 3: Relasi Keterkaitan Fitur
    story.append(Paragraph("3. Relasi Keterkaitan Fitur", heading_style))
    story.append(Spacer(1, 0.1 * inch))
    
    relasi_text = """
    Sistem ini dirancang dengan keterkaitan data yang erat antar fitur:
    <br/><br/>
    1. <b>Pendaftaran ke Siswa:</b> Data yang dikirim melalui formulir pendaftaran (Public) akan dikelola oleh Admin. Jika diterima, data tersebut dapat dikonversi menjadi data Siswa resmi.
    <br/><br/>
    2. <b>Siswa ke Orang Tua:</b> Setiap akun Orang Tua dihubungkan dengan satu atau lebih data Siswa (Anak). Hal ini memungkinkan Orang Tua melihat data yang spesifik hanya untuk anak mereka.
    <br/><br/>
    3. <b>Kelas & Jadwal:</b> Fitur Kelas menghubungkan Siswa, Guru, dan Mata Pelajaran. Jadwal Pelajaran disusun berdasarkan relasi ini.
    <br/><br/>
    4. <b>Akademik (Nilai & Presensi):</b> Data Nilai dan Presensi yang diinput oleh Admin/Guru akan langsung terdistribusi ke Dashboard Orang Tua masing-masing siswa yang bersangkutan secara real-time.
    <br/><br/>
    5. <b>Tahun Ajaran:</b> Semua data akademik (Kelas, Nilai, Jadwal) terikat pada Tahun Ajaran yang aktif, sehingga riwayat data tetap tersimpan dengan rapi setiap tahunnya.
    """
    story.append(Paragraph(relasi_text, normal_style))
    
    # Build
    doc.build(story)
    print("PDF generated successfully: Dokumentasi_Fitur_SD1Passo.pdf")

if __name__ == "__main__":
    generate_pdf()
