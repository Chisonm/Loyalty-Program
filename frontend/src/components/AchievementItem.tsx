import React from 'react'
import type { ComponentType } from 'react'
import type { IconsaxProps } from '../types'
import ProgressBar from './ProgressBar'

interface AchievementItemProps {
  icon: ComponentType<IconsaxProps> | React.ReactNode
  title: string
  description: string
  value: number
  max: number
  iconColor?: string
  iconSize?: string
  className?: string
}

const AchievementItem: React.FC<AchievementItemProps> = ({
  icon,
  title,
  description,
  value,
  max,
  iconColor = 'text-primary-green',
  iconSize = 'size-8',
  className = ''
}) => {
  const renderIcon = () => {
    if (React.isValidElement(icon)) {
      return icon
    }
    
    const IconComponent = icon as ComponentType<IconsaxProps>
    return <IconComponent className={`${iconColor} ${iconSize}`} variant="Bold" />
  }

  return (
    <div className={`flex items-center gap-4 ${className}`}>
      {/* icon */}
      <div className="flex-shrink-0">
        {renderIcon()}
      </div>
      
      {/* progress bar and achievement label with description */}
      <div className="flex flex-col gap-2 flex-1">
        <span className="text-[#344054] text-base font-medium">{title}</span>
        <span className="text-secondary text-sm font-medium">{description}</span>
        <ProgressBar
          value={value}
          max={max}
          color="bg-primary-green"
          backgroundColor="bg-gray-200/90"
          animated={true}
        />
      </div>
    </div>
  )
}

export default AchievementItem