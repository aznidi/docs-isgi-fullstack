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
import Founders from '../components/landing/Founders';
import SingleModulePage from '../pages/SingleModulePage';
import DocumentsAdminPage from '../pages/DocumentsAdminPage';
import AddDocument from '../components/admin/documents/AddDocument';
import EditDocument from '../components/admin/documents/EditDocument';
import SingleDocumentPage from '../pages/SingleDocumentPage';

export const HOME_ROUTE = '/';
export const LOGIN_ROUTE = '/login';
export const router = createBrowserRouter([
    {
      element: <Layout />, // Public Layout
      children: [
        { path: "/", element: <HomePage /> },
        { path: "/accueil", element: <HomePage /> },
        { path: "/modules", element: <ModulesPageUser /> },
        { path: "/modules/:id", element: <SingleModulePage /> },
        { path: "/documents", element: <p>Documents</p> },
        { path: "/documents/:id", element: <SingleDocumentPage /> },
        { path: "/contact-support", element: <ContactSupportPage /> },
        { path: "/founders", element: <Founders /> },
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
        { path: "/founders", element: <Founders /> },
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
        { path: "/founders", element: <Founders /> },
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
          { path: "/admin/documents", element: <DocumentsAdminPage /> },
          { path: "/admin/documents/add", element: <AddDocument /> },
          { path: "/admin/documents/edit/:id", element: <EditDocument /> },
          { path: "*", element: <NotFoundPage /> },
        ],
      }
  ]);
