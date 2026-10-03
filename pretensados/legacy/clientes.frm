VERSION 5.00
Object = "{00025600-0000-0000-C000-000000000046}#5.2#0"; "crystl32.ocx"
Begin VB.Form Clientes 
   Appearance      =   0  'Flat
   BackColor       =   &H00E0E0E0&
   BorderStyle     =   3  'Fixed Dialog
   ClientHeight    =   5970
   ClientLeft      =   2130
   ClientTop       =   2055
   ClientWidth     =   6690
   BeginProperty Font 
      Name            =   "MS Sans Serif"
      Size            =   8.25
      Charset         =   0
      Weight          =   700
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   ForeColor       =   &H80000008&
   HelpContextID   =   240
   Icon            =   "clientes.frx":0000
   LinkTopic       =   "Form2"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   PaletteMode     =   1  'UseZOrder
   ScaleHeight     =   5970
   ScaleWidth      =   6690
   ShowInTaskbar   =   0   'False
   StartUpPosition =   2  'CenterScreen
   Begin VB.CommandButton Command3D3 
      Appearance      =   0  'Flat
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   3360
      Picture         =   "clientes.frx":058A
      Style           =   1  'Graphical
      TabIndex        =   27
      ToolTipText     =   "Borrar"
      Top             =   5160
      Width           =   690
   End
   Begin VB.CommandButton Command3D1 
      Appearance      =   0  'Flat
      Height          =   495
      Left            =   1920
      Picture         =   "clientes.frx":0B14
      Style           =   1  'Graphical
      TabIndex        =   26
      ToolTipText     =   "Guardar los cambios"
      Top             =   5160
      Width           =   690
   End
   Begin VB.CommandButton Command3D2 
      Appearance      =   0  'Flat
      Height          =   495
      Left            =   2640
      Picture         =   "clientes.frx":0B95
      Style           =   1  'Graphical
      TabIndex        =   25
      ToolTipText     =   "Cancelar las modificaciones"
      Top             =   5160
      Width           =   690
   End
   Begin VB.CommandButton cmdPrint 
      Appearance      =   0  'Flat
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   4080
      Picture         =   "clientes.frx":0E9F
      Style           =   1  'Graphical
      TabIndex        =   24
      ToolTipText     =   "Imprimir"
      Top             =   5160
      Width           =   690
   End
   Begin VB.TextBox Text8 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   285
      Left            =   1320
      MaxLength       =   50
      TabIndex        =   22
      Text            =   "Text8"
      Top             =   4440
      Width           =   5055
   End
   Begin VB.TextBox Text7 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   285
      Left            =   1320
      MaxLength       =   50
      TabIndex        =   10
      Text            =   "Text7"
      Top             =   3960
      Width           =   5055
   End
   Begin VB.TextBox Text6 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   285
      Left            =   4440
      MaxLength       =   20
      TabIndex        =   9
      Text            =   "Text6"
      Top             =   3360
      Width           =   1935
   End
   Begin VB.ComboBox Combo4 
      Appearance      =   0  'Flat
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   315
      Left            =   1320
      Sorted          =   -1  'True
      TabIndex        =   8
      Text            =   "Combo4"
      Top             =   3360
      Width           =   2535
   End
   Begin VB.ComboBox Combo3 
      Appearance      =   0  'Flat
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   315
      Left            =   1320
      Sorted          =   -1  'True
      TabIndex        =   7
      Text            =   "Combo3"
      Top             =   2880
      Width           =   3495
   End
   Begin VB.TextBox Text5 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   285
      Left            =   4200
      MaxLength       =   20
      TabIndex        =   4
      Text            =   "Text5"
      Top             =   2040
      Width           =   2175
   End
   Begin VB.TextBox Text4 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   285
      Left            =   1320
      MaxLength       =   20
      TabIndex        =   3
      Text            =   "Text4"
      Top             =   2040
      Width           =   2175
   End
   Begin VB.TextBox Text3 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   285
      Left            =   5400
      TabIndex        =   6
      Text            =   "0"
      Top             =   2520
      Width           =   975
   End
   Begin VB.ComboBox Combo2 
      Appearance      =   0  'Flat
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   315
      Left            =   1320
      Sorted          =   -1  'True
      TabIndex        =   5
      Text            =   "Combo2"
      Top             =   2520
      Width           =   3495
   End
   Begin VB.TextBox Text2 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   285
      Left            =   1320
      MaxLength       =   50
      TabIndex        =   2
      Text            =   "Text2"
      Top             =   1680
      Width           =   5055
   End
   Begin VB.TextBox Text1 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   285
      Left            =   1320
      MaxLength       =   100
      TabIndex        =   1
      Text            =   "Text1"
      Top             =   1200
      Width           =   5055
   End
   Begin VB.ComboBox Combo1 
      Appearance      =   0  'Flat
      BackColor       =   &H00808080&
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00FFFFFF&
      Height          =   315
      Left            =   1320
      Sorted          =   -1  'True
      TabIndex        =   0
      Text            =   "Combo1"
      Top             =   240
      Width           =   5055
   End
   Begin Crystal.CrystalReport CrystalReport1 
      Left            =   0
      Top             =   0
      _ExtentX        =   741
      _ExtentY        =   741
      _Version        =   348160
      PrintFileLinesPerPage=   60
   End
   Begin VB.Label Label12 
      Appearance      =   0  'Flat
      AutoSize        =   -1  'True
      BackColor       =   &H80000005&
      BackStyle       =   0  'Transparent
      Caption         =   "E-mail"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00800000&
      Height          =   195
      Left            =   240
      TabIndex        =   23
      Top             =   4440
      Width           =   420
   End
   Begin VB.Label Label11 
      Appearance      =   0  'Flat
      AutoSize        =   -1  'True
      BackColor       =   &H80000005&
      BackStyle       =   0  'Transparent
      Caption         =   "CUIT"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00800000&
      Height          =   195
      Left            =   3960
      TabIndex        =   21
      Top             =   3360
      Width           =   375
   End
   Begin VB.Label Label10 
      Appearance      =   0  'Flat
      AutoSize        =   -1  'True
      BackColor       =   &H80000005&
      BackStyle       =   0  'Transparent
      Caption         =   "Contacto"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00800000&
      Height          =   195
      Left            =   240
      TabIndex        =   20
      Top             =   3960
      Width           =   645
   End
   Begin VB.Label Label9 
      Appearance      =   0  'Flat
      AutoSize        =   -1  'True
      BackColor       =   &H80000005&
      BackStyle       =   0  'Transparent
      Caption         =   "I.V.A."
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000080&
      Height          =   195
      Left            =   240
      TabIndex        =   19
      Top             =   3360
      Width           =   390
   End
   Begin VB.Label Label8 
      Appearance      =   0  'Flat
      AutoSize        =   -1  'True
      BackColor       =   &H80000005&
      BackStyle       =   0  'Transparent
      Caption         =   "Provincia"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000080&
      Height          =   195
      Left            =   240
      TabIndex        =   18
      Top             =   2880
      Width           =   660
   End
   Begin VB.Label Label7 
      Appearance      =   0  'Flat
      AutoSize        =   -1  'True
      BackColor       =   &H80000005&
      BackStyle       =   0  'Transparent
      Caption         =   "CP"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00800000&
      Height          =   195
      Left            =   5040
      TabIndex        =   17
      Top             =   2520
      Width           =   210
   End
   Begin VB.Label Label6 
      Appearance      =   0  'Flat
      AutoSize        =   -1  'True
      BackColor       =   &H80000005&
      BackStyle       =   0  'Transparent
      Caption         =   "Localidad"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000080&
      Height          =   195
      Left            =   240
      TabIndex        =   16
      Top             =   2520
      Width           =   690
   End
   Begin VB.Label Label5 
      Appearance      =   0  'Flat
      AutoSize        =   -1  'True
      BackColor       =   &H80000005&
      BackStyle       =   0  'Transparent
      Caption         =   "Fax"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00800000&
      Height          =   195
      Left            =   3720
      TabIndex        =   15
      Top             =   2040
      Width           =   255
   End
   Begin VB.Label Label4 
      Appearance      =   0  'Flat
      AutoSize        =   -1  'True
      BackColor       =   &H80000005&
      BackStyle       =   0  'Transparent
      Caption         =   "Teléfono"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00800000&
      Height          =   195
      Left            =   240
      TabIndex        =   14
      Top             =   2040
      Width           =   630
   End
   Begin VB.Label Label3 
      Appearance      =   0  'Flat
      AutoSize        =   -1  'True
      BackColor       =   &H80000005&
      BackStyle       =   0  'Transparent
      Caption         =   "Dirección"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00800000&
      Height          =   195
      Left            =   240
      TabIndex        =   13
      Top             =   1680
      Width           =   675
   End
   Begin VB.Line Line1 
      BorderColor     =   &H00800000&
      X1              =   6360
      X2              =   240
      Y1              =   840
      Y2              =   840
   End
   Begin VB.Label Label2 
      Appearance      =   0  'Flat
      AutoSize        =   -1  'True
      BackColor       =   &H80000005&
      BackStyle       =   0  'Transparent
      Caption         =   "Razón Social"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00800000&
      Height          =   195
      Left            =   240
      TabIndex        =   12
      Top             =   1200
      Width           =   945
   End
   Begin VB.Label Label1 
      Appearance      =   0  'Flat
      AutoSize        =   -1  'True
      BackColor       =   &H80000005&
      BackStyle       =   0  'Transparent
      Caption         =   "Código"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000C0&
      Height          =   195
      Left            =   600
      TabIndex        =   11
      Top             =   240
      Width           =   495
   End
End
Attribute VB_Name = "Clientes"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Dim Clte As Recordset
Dim TIva As Recordset
Dim Ciudad As Recordset
Dim Prov As Recordset

Dim Permiso As String
Private Sub Alta()
    Command3D1.Enabled = True
    Command3D2.Enabled = True
    Command3D3.Enabled = False
    Combo1.Enabled = False
    Combo1.Text = "(nuevo)"
'-----------------------------------------------------
    Text1.SetFocus '<<<
End Sub

Private Sub cmdPrint_Click()
    If InStr(Permiso, "I") = 0 Then
        MsgBox "No tiene autorización para Imprimir", vbCritical
        Exit Sub
    End If
    
    
    Listado "Clientes", CrystalReport1

End Sub

Private Sub Combo1_KeyPress(KeyAscii As Integer)
    If KeyAscii = 13 Then
        If Combo1.Text = "" Then
            Alta
            Exit Sub
        End If
        BCombo Me, Combo1, "Clientes", "RAZON-SOCIAL", "CODIGO", 2, "", Clte
        If Combo1.Tag <> "" Then
            TraigoDatos
            Command3D2.SetFocus
        End If
    End If
End Sub

Private Sub Combo2_GotFocus()
    Foco Combo2
End Sub


Private Sub Combo2_KeyPress(KeyAscii As Integer)
    If KeyAscii = 13 Then
        If Combo2 <> "" Then
            BCombo Me, Combo2, "Ciudades", "DESCRIPCION", "CODIGO", 2, "", Ciudad
            If Combo2.Tag <> "" Then
                If Combo1.Text = "(nuevo)" Then
                    If Not IsNull(Ciudad("Cod-Post")) Then Text3 = Ciudad("Cod-Post")
                End If
                Text3.SetFocus
            End If
        End If
    End If
End Sub


Private Sub Combo3_GotFocus()
    Foco Combo3
End Sub


Private Sub Combo3_KeyPress(KeyAscii As Integer)
    If KeyAscii = 13 Then
        If Combo3 <> "" Then
            BCombo Me, Combo3, "Provincias", "DESCRIPCION", "CODIGO", 2, "", Prov
            If Combo3.Tag <> "" Then Combo4.SetFocus
        End If
    End If
End Sub


Private Sub Combo4_GotFocus()
    Foco Combo4
End Sub


Private Sub Combo4_KeyPress(KeyAscii As Integer)
    If KeyAscii = 13 Then
        If Combo4 <> "" Then
                       
            BCombo Me, Combo4, "Iva", "DESCRIPCION", "CODIGO", 2, "", TIva
            If Combo4.Tag <> "" Then Text6.SetFocus
        End If
    End If
End Sub


Private Sub Command3D1_Click()
    EncontroVal = 0
    ValidoDatos
    If EncontroVal = 0 Then Grabo
End Sub

Private Sub Command3D2_Click()
    Limpio
    Combo1.SetFocus
End Sub

Private Sub Command3D3_Click()
    If InStr(Permiso, "B") = 0 Then
        MsgBox "No tiene autorización para dar de Baja", vbCritical
        Exit Sub
    End If
    
    
    EncontroVal = 0
    Verifico
    If EncontroVal = 0 Then
        res = MsgBox("¿ Confirma el borrado ?", 36)
        If res = 6 Then
            On Error GoTo ErrorHandlerB
            DBEngine.BeginTrans
            DB.Execute ("Delete * from CLIENTES where CODIGO = " & Combo1.Tag)
            DBEngine.CommitTrans
            Command3D2_Click
        Else
            Exit Sub
        End If
    Else
        MsgBox "No puede eliminarse !", 16
    End If
    Exit Sub
    
ErrorHandlerB:
    MsgBox "Operación Anulada", 16
    DBEngine.Rollback
    Command3D2_Click
    Exit Sub
End Sub

Private Sub TraigoDatos()
    Combo1.Enabled = False
    Command3D1.Enabled = True
    Command3D2.Enabled = True
    Command3D3.Enabled = True
    '-------------------------------------------------------
    'Campos <<<
    Clte.Index = "Codigo"
    Clte.Seek "=", Combo1.Tag
    If Not Clte.NoMatch Then
        If Clte("Razon-Social") <> "" Then Text1.Text = Clte("Razon-Social")
        If Clte("Direccion") <> "" Then Text2.Text = Clte("Direccion")
        If Clte("Telefono") <> "" Then Text4.Text = Clte("Telefono")
        If Clte("Fax") <> "" Then Text5.Text = Clte("Fax")
        Traigo Me, Ciudad, "Codigo", Clte("Localidad"), "Descripcion", Combo2
        If Clte("Cod-Postal") <> "" Then Text3.Text = Clte("Cod-Postal")
        Traigo Me, Prov, "Codigo", Clte("Provincia"), "Descripcion", Combo3
        Traigo Me, TIva, "Codigo", Clte("Iva"), "Descripcion", Combo4
        If Clte("Cuit") <> "" Then Text6.Text = Clte("Cuit")
        If Clte("Contacto") <> "" Then Text7.Text = Clte("Contacto")
        If Clte("E-Mail") <> "" Then Text8.Text = Clte("E-Mail")
    End If
End Sub


Private Sub Form_Activate()
    If InStr(Permiso, "C") = 0 Then
        MsgBox "No tiene autorización para Consultar", vbCritical
        Unload Me
    End If
End Sub

Private Sub Form_Load()
    Me.Caption = "CLIENTES"
    Set Clte = DB.OpenRecordset("CLIENTES")  '<<<
    Set TIva = DB.OpenRecordset("IVA")
    Set Ciudad = DB.OpenRecordset("CIUDADES")
    Set Prov = DB.OpenRecordset("PROVINCIAS")
    Limpio
    Lleno Ciudad, Combo2, "Descripcion"
    Lleno Prov, Combo3, "Descripcion"
    Lleno TIva, Combo4, "Descripcion"
    
    Permiso = qPermisos(ByVal gbNivel, "FAC01")
End Sub

Private Sub Grabo()
    If Combo1.Text = "(nuevo)" Then
        If InStr(Permiso, "A") = 0 Then
            MsgBox "No tiene autorización para dar de Alta", vbCritical
            Exit Sub
        End If
    Else
        If InStr(Permiso, "M") = 0 Then
            MsgBox "No tiene autorización para Modificar", vbCritical
            Exit Sub
        End If
    End If
    
    
    On Error GoTo ErrorHandler
    DBEngine.BeginTrans
    If Combo1.Text = "(nuevo)" Then
        Clte.AddNew '<<<
    Else
        Clte.Index = "CODIGO" '<<<
        Clte.Seek "=", Combo1.Tag '<<<
        If Not Clte.NoMatch Then Clte.Edit      '<<<
    End If
    Clte("Razon-Social") = Trim(Text1) '<<<
    Clte("Direccion") = Trim(Text2) '<<<
    Clte("Telefono") = Trim(Text4)
    Clte("Fax") = Trim(Text5)
    Clte("Localidad") = Trim(Combo2.Tag)
    If IsNumeric(Text3) Then Clte("Cod-Postal") = Trim(Text3) '<<<
    Clte("Provincia") = Trim(Combo3.Tag) '<<<
    Clte("Iva") = Trim(Combo4.Tag) '<<<
    Clte("Cuit") = Trim(Text6)
    Clte("Contacto") = Trim(Text7)
    Clte("E-mail") = Trim(Text8)
    Clte("IdStatus") = "N"  'Normal
    Clte.Update '<<<
    DBEngine.CommitTrans
    Command3D2_Click
    Exit Sub
    
ErrorHandler:
    MsgBox "Operación anulada", 16
    DBEngine.Rollback
    Command3D2_Click
    Exit Sub
End Sub

Private Sub Limpio()
    Combo1.Clear
    Text1.Text = ""
    Text2.Text = ""
    Text3.Text = ""
    Text4.Text = ""
    Text5.Text = ""
    Text6.Text = ""
    Text7.Text = ""
    Text8.Text = ""
    Combo2.Text = ""
    Combo2.Tag = ""
    Combo3.Text = ""
    Combo3.Tag = ""
    Combo4.Text = ""
    Combo4.Tag = ""
    Command3D1.Enabled = False
    Command3D2.Enabled = False
    Command3D3.Enabled = False
    Combo1.Enabled = True
End Sub

Private Sub Label6_Click()
    Ciudades.Show 1
    Combo2.Clear
    Lleno Ciudad, Combo2, "Descripcion"
End Sub

Private Sub Label8_Click()
    Provincias.Show 1
    Combo3.Clear
    Lleno Prov, Combo3, "Descripcion"
End Sub

Private Sub Label9_Click()
    Iva.Show 1
    Combo4.Clear
    Lleno TIva, Combo4, "Descripcion"
End Sub


Private Sub Text1_KeyPress(KeyAscii As Integer)
    If KeyAscii = 13 Then
        Text1.Text = UCase(Text1.Text)
        If Text1.Text <> "" Then
            Clte.Index = "RAZON-SOCIAL"       '<<<
            Clte.Seek "=", Trim(Text1.Text)  '<<<
            If Not Clte.NoMatch Then
                If Combo1.Text = "(nuevo)" Then
                    MsgBox "Ya existe !", 48 '<<<
                Else
                    If Trim(Clte("CODIGO")) = Trim(Combo1.Tag) Then
                        Text2.SetFocus
                    Else
                        MsgBox "Ya existe !", 48 '<<<
                    End If
                End If
            Else
                Text2.SetFocus
            End If
        End If
    End If
End Sub

Private Sub ValidoDatos()
    If Text1.Text = "" Then
        EncontroVal = 1
        Text1.SetFocus
        Exit Sub
    Else
        Clte.Index = "Razon-Social"       '<<<
        Clte.Seek "=", Trim(Text1.Text)  '<<<
        If Not Clte.NoMatch Then         '<<<
            If Combo1.Text = "(nuevo)" Then
                EncontroVal = 1
                Text1.Text = ""
                Text1.SetFocus
                Exit Sub
            Else
                If Trim(Clte("CODIGO")) <> Trim(Combo1.Tag) Then '<<<
                    EncontroVal = 1
                    Text1.Text = ""
                    Text1.SetFocus
                    Exit Sub
                End If
            End If
        Else
            Valido Me, Ciudad, "Descripcion", Combo2.Text, "Codigo", Combo2
            If EncontroVal = 1 Then
                Combo2.Text = ""
                Combo2.Tag = ""
                Combo2.SetFocus
                Exit Sub
            End If
            Valido Me, Prov, "Descripcion", Combo3.Text, "Codigo", Combo3
            If EncontroVal = 1 Then
                Combo3.Text = ""
                Combo3.Tag = ""
                Combo3.SetFocus
                Exit Sub
            End If
            Valido Me, TIva, "Descripcion", Combo4.Text, "Codigo", Combo4
            If EncontroVal = 1 Then
                Combo4.Text = ""
                Combo4.Tag = ""
                Combo4.SetFocus
                Exit Sub
            End If
        End If
    End If
End Sub

Private Sub Verifico()

End Sub

Private Sub Text2_KeyPress(KeyAscii As Integer)
    If KeyAscii = 13 Then
        Text4.SetFocus
    End If
End Sub


Private Sub Text3_KeyPress(KeyAscii As Integer)
    If KeyAscii = 13 Then
        If Not IsNumeric(Text3) Then
            Text3 = ""
        Else
            Combo3.SetFocus
        End If
    End If
End Sub


Private Sub Text4_KeyPress(KeyAscii As Integer)
    If KeyAscii = 13 Then
        Text5.SetFocus
    End If
End Sub


Private Sub Text5_KeyPress(KeyAscii As Integer)
    If KeyAscii = 13 Then
        Combo2.SetFocus
    End If
End Sub


Private Sub Text6_KeyPress(KeyAscii As Integer)
    If KeyAscii = 13 Then
        Text7.SetFocus
    End If
End Sub


Private Sub Text7_KeyPress(KeyAscii As Integer)
    If KeyAscii = 13 Then
        Text8.SetFocus
    End If
End Sub


Private Sub Text8_KeyPress(KeyAscii As Integer)
    If KeyAscii = 13 Then
        If Command3D1.Enabled = True Then Command3D1.SetFocus
    End If
End Sub


