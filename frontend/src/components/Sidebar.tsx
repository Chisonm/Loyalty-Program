import React from 'react'
import type { SidebarProps } from '../types'

const Sidebar: React.FC<SidebarProps> = ({
  isOpen,
  onToggle,
  navItems,
  title = 'Dashboard',
  className = ''
}) => {
  return (
    <>
      {/* Sidebar */}
      <aside className={`${
        isOpen ? 'translate-x-0' : '-translate-x-full'
      } fixed inset-y-0 left-0 z-50 w-64 bg-white border-r custom-border-color transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:flex-shrink-0 ${className}`}>
        <div className="flex flex-col h-full">
          <div className="flex items-center justify-between h-16 px-6 border-b custom-border-color">
            <h1 className="text-xl font-semibold text-gray-800">{title}</h1>
            <button
              onClick={onToggle}
              className="p-2 rounded-md lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <nav className="flex-1 mt-6 overflow-y-auto">
            <ul className="space-y-1 px-4 pb-4">
              {navItems.map((item) => (
                <li key={item.id}>
                  <a
                    href={item.href}
                    className={`flex items-center px-3 py-2 text-base font-semibold rounded-lg transition-colors ${
                      item.active
                        ? 'bg-primary-green-light text-primary-green border-l-4 border-primary-green'
                        : 'text-gray-600 hover:bg-primary-green-light hover:text-primary-green'
                    }`}
                  >
                    {item.icon && (
                      <span className="mr-2">
                        <item.icon 
                          className="size-5" 
                          variant="Linear"
                        />
                      </span>
                    )}
                    {item.label}
                  </a>
                </li>
              ))}
            </ul>
          </nav>
        </div>
      </aside>

      {/* Mobile overlay */}
      {isOpen && (
        <div
          className="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"
          onClick={onToggle}
        />
      )}
    </>
  )
}

export default Sidebar