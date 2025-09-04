import { Routes, Route, useLocation } from 'react-router-dom'
import AppLayout from "./layout/app-layout"
import Dashboard from "./pages/Dashboard"
import UserStats from "./pages/UserStats"
import { Category, ShoppingBag, Tag, Setting2, TrendUp } from "iconsax-reactjs"
import type { SidebarNavItem } from "./types"

function App() {
  const location = useLocation()

  const navItems: SidebarNavItem[] = [
    { 
      id: "1", 
      icon: TrendUp, 
      label: "Users", 
      href: "/", 
      active: location.pathname === '/'
    },
    { 
      id: "2", 
      icon: ShoppingBag, 
      label: "Orders", 
      href: "/orders", 
      active: location.pathname === '/orders'
    },
    { 
      id: "3", 
      icon: Tag, 
      label: "Products", 
      href: "/products", 
      active: location.pathname === '/products'
    },
    { 
      id: "4", 
      icon: Category, 
      label: "Analytics", 
      href: "/analytics", 
      active: location.pathname === '/analytics'
    },
    { 
      id: "5", 
      icon: Setting2, 
      label: "Settings", 
      href: "/settings", 
      active: location.pathname === '/settings'
    },
  ]

  return (
    <AppLayout navItems={navItems}>
      <Routes>
        <Route path="/" element={<Dashboard />} />
        <Route path="/user/:userId/achievements" element={<UserStats />} />
      </Routes>
    </AppLayout>
  )
}

export default App;
