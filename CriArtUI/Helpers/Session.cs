namespace CriArtUI.Helpers
{
    public static class Session
    {
        public static int UserID { get; set; }
        public static string UserName { get; set; }
        public static string Role { get; set; }
        public static int CustomerID { get; set; }

        public static bool IsAdmin => Role == "Admin"; // Helper property to check if the user is an admin

        public static void Clear()
        {
            UserID = 0;
            UserName = null;
            Role = null;
            CustomerID = 0;
        }
    }
}
