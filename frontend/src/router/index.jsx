import { createBrowserRouter } from 'react-router-dom';
import  HomePage  from '../pages/HomePage';
import  LoginPage  from '../pages/LoginPage';
import  SignupPage  from '../pages/SignupPage';
import  ProfilePage  from '../pages/ProfilePage';
import NotFoundPage from '../pages/NotFoundPage';
import GuestLayout from '../layouts/GuestLayout';
import Layout from '../layouts/Layout';
import AuthLayout from '../layouts/AuthLayout';
import ForgotPasswordPage from '../pages/ForgotPasswordPage';
import ResetPasswordPage from '../pages/ResetPasswordPage';
import AdminLayout from '../layouts/AdminLayout';
import AdminDashboardPage from '../pages/AdminDashboardPage';
import RequireAdmin from '../components/RequireAdmin';
import UsersPage from '../pages/UsersPage';
import ProfileAdmin from '../pages/ProfileAdmin';
import SettingsPage from '../pages/SettingsPage';
import ModulesPage from '../pages/ModulesPage';
import GoogleCallback from '../pages/GoogleCallback';
import ModulesPageUser from '../pages/ModulesPageUser';
import ContactSupportPage from '../pages/ContactSupportPage';

export const HOME_ROUTE = '/';
export const LOGIN_ROUTE = '/login';
export const router = createBrowserRouter([
    {
      element: <Layout />, // Public Layout
      children: [
        { path: "/", element: <HomePage /> },
        { path: "/accueil", element: <HomePage /> },
        { path: "/modules", element: <ModulesPageUser /> },
        { path: "/documents", element: <p>Documents</p> },
        { path: "/contact-support", element: <ContactSupportPage /> },
        { path: "*", element: <NotFoundPage /> },
      ],
    },
    {
      element: <GuestLayout />, // Layout pour les invités
      children: [
        { path: "/login", element: <LoginPage /> },
        { path: "/sign-up", element: <SignupPage /> },
        { path: "/forgot-password", element: <ForgotPasswordPage /> },
        { path: "/password-reset/:token", element: <ResetPasswordPage /> },
        { path: "/login/callback", element: <GoogleCallback /> },
        { path: "/contact-support", element: <ContactSupportPage /> },
        { path: "/accueil", element: <HomePage /> },
        { path: "*", element: <NotFoundPage /> },
      ],
    },
    {
      element: <AuthLayout />, // Layout pour les utilisateurs connectés
      children: [
        { path: "/profile", element: <ProfilePage /> },
        { path: "/favorites", element: <p>Favoris</p> },
        { path: "/contact-support", element: <ContactSupportPage /> },
        { path: "*", element: <NotFoundPage /> },
        { path: "/accueil", element: <HomePage /> },
      ],
    },
    {
        element: (
          <RequireAdmin>
            <AdminLayout />
          </RequireAdmin>
        ),
        children: [
          { path: "/admin/dashboard", element: <AdminDashboardPage /> },
          { path: "/admin/users", element: <UsersPage /> },
          { path: "/admin/profile", element: <ProfileAdmin /> },
          { path: "/admin/settings", element: <SettingsPage /> },
          { path: "/admin/modules", element: <ModulesPage /> },
          { path: "*", element: <NotFoundPage /> },
        ],
      }
  ]);
