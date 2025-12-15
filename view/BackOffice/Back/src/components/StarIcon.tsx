import { motion } from 'motion/react';

interface StarIconProps {
  className?: string;
  animate?: boolean;
  delay?: number;
}

export function StarIcon({ className = '', animate = true, delay = 0 }: StarIconProps) {
  return (
    <motion.svg
      width="24"
      height="24"
      viewBox="0 0 24 24"
      fill="currentColor"
      className={className}
      initial={animate ? { scale: 0, rotate: -180 } : {}}
      animate={animate ? { 
        scale: [0, 1.2, 1],
        rotate: [0, 0, 0],
        opacity: [0, 1, 1]
      } : {}}
      transition={{
        duration: 0.6,
        delay,
        ease: "easeOut"
      }}
    >
      <path d="M12 2L14.09 8.26L20 9.27L15.45 13.14L16.91 19L12 15.77L7.09 19L8.55 13.14L4 9.27L9.91 8.26L12 2Z" />
    </motion.svg>
  );
}

export function TwinkleStars({ count = 5 }: { count?: number }) {
  return (
    <>
      {Array.from({ length: count }).map((_, i) => (
        <motion.div
          key={i}
          className="absolute text-cream"
          style={{
            left: `${Math.random() * 100}%`,
            top: `${Math.random() * 100}%`,
          }}
          animate={{
            opacity: [0, 1, 0],
            scale: [0.5, 1, 0.5],
          }}
          transition={{
            duration: 2 + Math.random() * 2,
            repeat: Infinity,
            delay: Math.random() * 2,
            ease: "easeInOut"
          }}
        >
          <StarIcon animate={false} className="w-4 h-4" />
        </motion.div>
      ))}
    </>
  );
}
