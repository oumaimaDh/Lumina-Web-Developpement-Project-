import { MessageCircle, Heart, Share2, Send } from "lucide-react";
import { useState } from "react";

interface FeedPostProps {
  avatar: string;
  name: string;
  message: string;
  timeAgo?: string;
}

interface Comment {
  id: number;
  author: string;
  text: string;
  timeAgo: string;
}

export function FeedPost({ avatar, name, message, timeAgo = "Just now" }: FeedPostProps) {
  const [isLiked, setIsLiked] = useState(false);
  const [likeCount, setLikeCount] = useState(Math.floor(Math.random() * 20) + 5);
  const [showComments, setShowComments] = useState(false);
  const [comments, setComments] = useState<Comment[]>([
    { id: 1, author: "Ahmed K.", text: "This sounds amazing! Count me in! 🙌", timeAgo: "5 min ago" },
    { id: 2, author: "Sarah M.", text: "Great initiative! Looking forward to it", timeAgo: "10 min ago" }
  ]);
  const [newComment, setNewComment] = useState("");

  const handleLike = () => {
    if (isLiked) {
      setLikeCount(likeCount - 1);
    } else {
      setLikeCount(likeCount + 1);
    }
    setIsLiked(!isLiked);
  };

  const handleAddComment = (e: React.FormEvent) => {
    e.preventDefault();
    if (newComment.trim()) {
      setComments([
        {
          id: comments.length + 1,
          author: "You",
          text: newComment,
          timeAgo: "Just now"
        },
        ...comments
      ]);
      setNewComment("");
      setShowComments(true);
    }
  };

  return (
    <div className="group bg-gradient-to-br from-white to-[#f5f0ed] rounded-3xl border-2 border-[#8195b8]/20 hover:border-[#8195b8] hover:shadow-xl transition-all duration-300 overflow-hidden">
      <div className="p-6">
        <div className="flex gap-4 mb-4">
          <img 
            src={avatar} 
            alt={name}
            className="w-14 h-14 rounded-full object-cover flex-shrink-0 ring-4 ring-[#8195b8]/20 group-hover:ring-[#8195b8]/40 transition-all"
          />
          <div className="flex-1">
            <div className="flex items-center justify-between mb-1">
              <div>
                <h4 className="text-[#1e3a5f] flex items-center gap-2">
                  {name}
                  <span className="text-lg">👋</span>
                </h4>
                <div className="flex items-center gap-2 text-[#8195b8] text-xs mt-1">
                  <span>{timeAgo}</span>
                  <span>•</span>
                  <button className="hover:text-[#5b7ba4] transition-colors">Follow</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <p className="text-[#5b7ba4] leading-relaxed mb-4">{message}</p>
        
        {/* Friendly Tags */}
        <div className="flex flex-wrap gap-2 mb-4">
          <span className="px-3 py-1.5 bg-gradient-to-r from-[#8195b8]/20 to-[#6b7da8]/20 text-[#5b7ba4] rounded-full text-xs border border-[#8195b8]/20 hover:border-[#8195b8] transition-colors">
            #Community 🤝
          </span>
          <span className="px-3 py-1.5 bg-gradient-to-r from-[#8195b8]/20 to-[#6b7da8]/20 text-[#5b7ba4] rounded-full text-xs border border-[#8195b8]/20 hover:border-[#8195b8] transition-colors">
            #Volunteer ✨
          </span>
        </div>
      </div>
      
      {/* Friendly Actions */}
      <div className="border-t border-[#8195b8]/20 px-6 py-4 flex items-center gap-6 bg-white/50">
        <button 
          onClick={handleLike}
          className={`flex items-center gap-2 transition-colors group/btn ${
            isLiked ? 'text-[#5b7ba4]' : 'text-[#8195b8] hover:text-[#5b7ba4]'
          }`}
        >
          <Heart className={`w-5 h-5 group-hover/btn:scale-110 transition-all ${
            isLiked ? 'fill-[#5b7ba4]' : ''
          }`} />
          <span className="text-sm">{isLiked ? 'Loved!' : 'Love it!'} ({likeCount})</span>
        </button>
        <button 
          onClick={() => setShowComments(!showComments)}
          className="flex items-center gap-2 text-[#8195b8] hover:text-[#5b7ba4] transition-colors"
        >
          <MessageCircle className="w-5 h-5" />
          <span className="text-sm">Chat ({comments.length})</span>
        </button>
        <button className="flex items-center gap-2 text-[#8195b8] hover:text-[#5b7ba4] transition-colors">
          <Share2 className="w-5 h-5" />
          <span className="text-sm">Share</span>
        </button>
      </div>

      {/* Comments Section */}
      {showComments && (
        <div className="border-t border-[#8195b8]/20 bg-[#f5f0ed]/30 px-6 py-4">
          {/* Add Comment Form */}
          <form onSubmit={handleAddComment} className="mb-4">
            <div className="flex gap-2">
              <input
                type="text"
                value={newComment}
                onChange={(e) => setNewComment(e.target.value)}
                placeholder="Add a friendly comment... 💬"
                className="flex-1 px-4 py-2 rounded-full border-2 border-[#8195b8]/20 focus:border-[#8195b8] outline-none text-[#1e3a5f] bg-white"
              />
              <button
                type="submit"
                className="bg-gradient-to-r from-[#6b7da8] to-[#8195b8] text-white p-2 rounded-full hover:shadow-lg transition-all"
              >
                <Send className="w-5 h-5" />
              </button>
            </div>
          </form>

          {/* Comments List */}
          <div className="space-y-3">
            {comments.map((comment) => (
              <div key={comment.id} className="bg-white rounded-2xl p-3 border border-[#8195b8]/10">
                <div className="flex items-start gap-3">
                  <div className="w-8 h-8 rounded-full bg-gradient-to-br from-[#6b7da8] to-[#8195b8] flex items-center justify-center text-white text-xs flex-shrink-0">
                    {comment.author[0]}
                  </div>
                  <div className="flex-1">
                    <div className="flex items-center gap-2 mb-1">
                      <span className="text-[#1e3a5f] text-sm">{comment.author}</span>
                      <span className="text-[#8195b8] text-xs">• {comment.timeAgo}</span>
                    </div>
                    <p className="text-[#5b7ba4] text-sm">{comment.text}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
}