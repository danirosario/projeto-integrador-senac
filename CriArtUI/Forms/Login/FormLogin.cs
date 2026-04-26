using CriArtUI.Helpers;
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace CriArtUI
{
    public partial class Login : Form
    {
        public Login()
        {
            InitializeComponent();
        }

        private void ButtonEntrar_Click(object sender, EventArgs e)
        {
            var user = _authService.Login(textBoxEmail.Text, textBoxSenha.Text);
            if (user == null) 
            { 
                MessageBox.Show("Credenciais inválidas"); 
                return; 
            }

            Session.UserID     = user.IdUser;
            Session.UserName   = user.Name;
            Session.Role       = user.RoleName;
            Session.CustomerID = user.CustomerId;

            this.Hide();
            if (Session.IsAdmin)
                new FormDashboardAdmin().Show();
            else
                new FormCatalogo().Show();
        }
    }
}
